<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\AreaGuide;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAreaGuides extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'area-guides:generate {--force : Force overwrite existing guides}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate area guides from existing property locations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating Area Guides from Properties...');
        
        // Get all unique cities from properties
        $cities = Property::whereNotNull('city')
            ->select('city', 'state')
            ->distinct()
            ->get();
        
        if ($cities->isEmpty()) {
            $this->error('No cities found in properties. Please add properties first.');
            return 1;
        }
        
        $this->info("Found " . $cities->count() . " unique locations.");
        
        $created = 0;
        $skipped = 0;
        
        foreach ($cities as $location) {
            $city = $location->city;
            $state = $location->state ?? 'Kenya';
            
            // Check if guide already exists
            $existing = AreaGuide::where('slug', Str::slug($city))->first();
            
            if ($existing && !$this->option('force')) {
                $this->line("Skipping {$city} (already exists)");
                $skipped++;
                continue;
            }
            
            // Generate area guide data
            $guideData = $this->generateGuideData($city, $state);
            
            if ($existing) {
                $existing->update($guideData);
                $this->info("Updated guide for {$city}");
            } else {
                AreaGuide::create($guideData);
                $this->info("Created guide for {$city}");
            }
            
            $created++;
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("Created/Updated: {$created}");
        $this->info("Skipped: {$skipped}");
        $this->info("Total Locations: " . $cities->count());
        
        return 0;
    }
    
    /**
     * Generate area guide data for a location
     */
    private function generateGuideData($city, $state)
    {
        // Get properties in this city to gather data
        $properties = Property::where('city', $city)->get();
        
        // Build amenities from property data
        $amenities = $this->extractAmenities($properties);
        
        // Generate description
        $description = $this->generateDescription($city, $state, $properties);
        
        return [
            'name' => $city,
            'slug' => Str::slug($city),
            'description' => $description,
            'featured_image' => $this->getFeaturedImage($city),
            'amenities' => json_encode($amenities),
            'nearby_places' => json_encode($this->getNearbyPlaces($city)),
            'transport' => json_encode($this->getTransportInfo($city)),
            'coordinates' => json_encode($this->getCoordinates($city)),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
    
    /**
     * Extract amenities from properties
     */
    private function extractAmenities($properties)
    {
        $amenities = [
            'schools' => [],
            'hospitals' => [],
            'sports' => [],
            'shopping' => [],
        ];
        
        // Collect all amenities from properties
        $allAmenities = [];
        foreach ($properties as $property) {
            if ($property->amenities && is_array($property->amenities)) {
                foreach ($property->amenities as $amenity) {
                    $name = is_array($amenity) ? ($amenity['amenity'] ?? '') : $amenity;
                    if ($name) {
                        $allAmenities[] = $name;
                    }
                }
            }
        }
        
        // Categorize amenities
        $schoolKeywords = ['school', 'college', 'university', 'academy', 'campus', 'institute', 'education'];
        $hospitalKeywords = ['hospital', 'clinic', 'medical', 'health', 'doctor', 'pharmacy', 'dispensary'];
        $sportKeywords = ['gym', 'fitness', 'sport', 'club', 'pool', 'tennis', 'football', 'stadium'];
        $shoppingKeywords = ['mall', 'shop', 'supermarket', 'store', 'market', 'centre', 'retail'];
        
        foreach ($allAmenities as $amenity) {
            $amenityLower = strtolower($amenity);
            
            if ($this->containsKeyword($amenityLower, $schoolKeywords) && count($amenities['schools']) < 5) {
                $amenities['schools'][] = $amenity;
            } elseif ($this->containsKeyword($amenityLower, $hospitalKeywords) && count($amenities['hospitals']) < 5) {
                $amenities['hospitals'][] = $amenity;
            } elseif ($this->containsKeyword($amenityLower, $sportKeywords) && count($amenities['sports']) < 5) {
                $amenities['sports'][] = $amenity;
            } elseif ($this->containsKeyword($amenityLower, $shoppingKeywords) && count($amenities['shopping']) < 5) {
                $amenities['shopping'][] = $amenity;
            }
        }
        
        // Add default amenities if empty
        if (empty($amenities['schools'])) {
            $amenities['schools'] = $this->getDefaultSchools($city);
        }
        if (empty($amenities['hospitals'])) {
            $amenities['hospitals'] = $this->getDefaultHospitals($city);
        }
        if (empty($amenities['sports'])) {
            $amenities['sports'] = $this->getDefaultSports($city);
        }
        if (empty($amenities['shopping'])) {
            $amenities['shopping'] = $this->getDefaultShopping($city);
        }
        
        return $amenities;
    }
    
    /**
     * Check if text contains any keyword
     */
    private function containsKeyword($text, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Generate description for the area
     */
    private function generateDescription($city, $state, $properties)
    {
        $propertyCount = $properties->count();
        $priceRange = $this->getPriceRange($properties);
        
        $descriptions = [
            "{$city} is a vibrant and sought-after residential and commercial neighborhood in {$state}.",
            "Known for its excellent amenities and strategic location, {$city} offers a perfect blend of urban convenience and suburban tranquility.",
            "With {$propertyCount} properties currently listed, the area features a diverse range of housing options to suit various lifestyles and budgets.",
            "Properties in {$city} typically range from {$priceRange}, making it an attractive destination for both homebuyers and investors.",
            "The neighborhood is well-connected with excellent transport links and is close to major business districts, schools, hospitals, and recreational facilities.",
        ];
        
        return implode(' ', $descriptions);
    }
    
    /**
     * Get price range for the area
     */
    private function getPriceRange($properties)
    {
        if ($properties->isEmpty()) {
            return 'varying prices';
        }
        
        $prices = $properties->pluck('price')->filter();
        if ($prices->isEmpty()) {
            return 'various price points';
        }
        
        $min = number_format($prices->min() / 1000000, 1) . 'M';
        $max = number_format($prices->max() / 1000000, 1) . 'M';
        
        return "KSh {$min} to {$max}";
    }
    
    /**
     * Get featured image for the area
     */
    private function getFeaturedImage($city)
    {
        // Try to find a property with an image in this city
        $property = Property::where('city', $city)
            ->whereNotNull('images')
            ->where('images', '!=', '[]')
            ->first();
        
        if ($property && $property->images && is_array($property->images)) {
            return $property->images[0] ?? null;
        }
        
        return null;
    }
    
    /**
     * Get nearby places for the area
     */
    private function getNearbyPlaces($city)
    {
        $places = [
            'Nairobi' => ['Nairobi National Park', 'Kenyatta International Convention Centre', 'Nairobi Railway Museum'],
            'Mombasa' => ['Fort Jesus', 'Mombasa Old Town', 'Nyali Beach'],
            'Kisumu' => ['Lake Victoria', 'Kisumu Impala Sanctuary', 'Kit Mikayi'],
            'Nakuru' => ['Lake Nakuru National Park', 'Menengai Crater', 'Hyrax Hill Museum'],
            'Eldoret' => ['Eldoret Golf Club', 'Kipchoge Keino Stadium', 'Poison Ivory'],
        ];
        
        return $places[$city] ?? ['Central Business District', 'Local Markets', 'Community Parks'];
    }
    
    /**
     * Get transport information
     */
    private function getTransportInfo($city)
    {
        return [
            'public_transport' => 'Matatus, buses, and taxis available',
            'roads' => 'Well-connected road network',
            'airport' => 'Nearby airport access',
            'railway' => 'SGR and commuter rail connections',
        ];
    }
    
    /**
     * Get coordinates for the city
     */
    private function getCoordinates($city)
    {
        $coordinates = [
            'Nairobi' => ['lat' => -1.2921, 'lng' => 36.8219],
            'Mombasa' => ['lat' => -4.0435, 'lng' => 39.6682],
            'Kisumu' => ['lat' => -0.0917, 'lng' => 34.7680],
            'Nakuru' => ['lat' => -0.3031, 'lng' => 36.0800],
            'Eldoret' => ['lat' => 0.5143, 'lng' => 35.2698],
            'Thika' => ['lat' => -1.0388, 'lng' => 37.0833],
            'Malindi' => ['lat' => -3.2186, 'lng' => 40.1169],
        ];
        
        return $coordinates[$city] ?? ['lat' => 0, 'lng' => 0];
    }
    
    /**
     * Default schools by city
     */
    private function getDefaultSchools($city)
    {
        $schools = [
            'Nairobi' => ['Nairobi School', 'State House Girls', 'St. Mary\'s School', 'Brookhouse School'],
            'Mombasa' => ['Mombasa High School', 'Serani High School', 'Aga Khan Academy'],
            'Kisumu' => ['Kisumu Boys High School', 'Kisumu Girls High School', 'Aga Khan Primary School'],
        ];
        
        return $schools[$city] ?? ['Local Primary School', 'Local Secondary School', 'International School'];
    }
    
    /**
     * Default hospitals by city
     */
    private function getDefaultHospitals($city)
    {
        $hospitals = [
            'Nairobi' => ['Nairobi Hospital', 'Aga Khan University Hospital', 'Kenyatta National Hospital'],
            'Mombasa' => ['Coast General Hospital', 'Aga Khan Hospital Mombasa', 'Pandya Memorial Hospital'],
            'Kisumu' => ['Jaramogi Oginga Odinga Teaching Hospital', 'Aga Khan Hospital Kisumu'],
        ];
        
        return $hospitals[$city] ?? ['General Hospital', 'Private Clinic', 'Community Health Centre'];
    }
    
    /**
     * Default sports facilities by city
     */
    private function getDefaultSports($city)
    {
        $sports = [
            'Nairobi' => ['Nairobi Gymkhana', 'Royal Nairobi Golf Club', 'Nairobi Sports Club'],
            'Mombasa' => ['Mombasa Sports Club', 'Nyali Golf Club', 'Bamburi Sports Club'],
            'Kisumu' => ['Kisumu Sports Club', 'Lake Victoria Golf Club'],
        ];
        
        return $sports[$city] ?? ['Community Sports Centre', 'Local Gym', 'Public Park'];
    }
    
    /**
     * Default shopping by city
     */
    private function getDefaultShopping($city)
    {
        $shopping = [
            'Nairobi' => ['The Village Market', 'Westgate Mall', 'Sarit Centre', 'Two Rivers Mall'],
            'Mombasa' => ['City Mall', 'Nyali Cinemax', 'Naivas Supermarket'],
            'Kisumu' => ['West End Mall', 'Victoria Gardens', 'Naivas Supermarket'],
        ];
        
        return $shopping[$city] ?? ['Local Shopping Centre', 'Supermarket', 'Community Market'];
    }
}