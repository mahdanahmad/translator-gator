<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Example list, change this array to any Category add Category Item that will be used,
        // the key will be Category and the values will be Category Items under it's parent.
        // Create a new key to add new category.
        $list   = array(
            'Goverment Priorities' => array(
                'Food Suffeciency',
                'Energy Security',
                'Maritime Development',
                'Infrastructure and Transportation',
                'Education',
                'Health',
                'Poverty Reduction',
                'Bureaucratic Reform',
                'Tourism',
                'Industry'
            ),
            'SDGs (Sustainable Development Goals)' => array(
                'No poverty',
                'Zero hunger',
                'Good health and well-being',
                'Quality education',
                'Gender equality',
                'Clean water and sanitation',
                'Affordable and clean energy',
                'Decent work and economic growth',
                'Industry, innovation and infrastructure',
                'Reduced inequalities',
                'Sustainable cities and communities',
                'Responsible consumption and production',
                'Climate action',
                'Life below water',
                'Life on land',
                'Peace, justice and strong institution',
                'Partnership for the goals'
            ),
    	);

        $categories = array_keys($list);

        DB::collection('categories')->delete();
        DB::collection('categories')->insert(array_map(function($o) { return array('category_group' => $o); }, $categories));

        $category_items = array();
        foreach ($categories as $category) {
            $current_id     = Category::where('category_group', $category)->first()->_id;
            $category_items = array_merge($category_items, array_map(function($o) use ($current_id) { return array('category_name' => $o, 'category_id' => $current_id); }, array_merge($list[$category], array('other'))));
        }

		DB::collection('category_items')->delete();
		DB::collection('category_items')->insert($category_items);
    }
}
