<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use URL;


/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;



    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('article', 'articles');
    }

    // protected function fetchTag()
    // {
    //     return $this->fetch(\App\Models\Tag::class);
    // }

    protected function fetchtags()
    {
        return $this->fetch(\App\Models\Tag::class);
    }
    protected function fetchcategories()
    {
        return $this->fetch(\App\Models\Category::class);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
        CRUD::column('content');
      //  CRUD::column('image');
        CRUD::column('status_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        CRUD::addColumn([
            'name' => 'image', // The db column name
            'label' => "Article image", // Table column heading
            'type' => 'image',
              'prefix' => URL::to('/api/v1/photo'),
             // image from a different disk (like s3 bucket)
             // 'disk' => 'disk-name', 
             // optional width/height if 25px is not ok with you
             // 'height' => '30px',
             // 'width' => '30px',
             
         ]); 

        CRUD::addColumn(
            
            [  
                // any type of relationship
                'name'         => 'tags', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'Tags', // Table column heading
                // OPTIONAL
                 'entity'    => 'tags', // the method that defines the relationship in your Model
                 'attribute' => 'name', // foreign key attribute that is shown to user
                 'model'     => App\Models\Tag::class, // foreign key model
             ],
        );
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticleRequest::class);

        CRUD::field('title');
        CRUD::field('content');
      //  CRUD::field('image')->type('image');
        CRUD::field('status_id');

        $this->crud->addField(
            [
            'label' => "Article Image",
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
        //    'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'disk' => 's3_bucket', // in case you need to show images from a different disk
             'prefix' => URL::to('/api/v1/photo')  // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);

        CRUD::addField(
            
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Tags",
                'type'      => 'relationship',
                'name'      => 'tags', // the method that defines the relationship in your Model
           
                //   'ajax'          => true,
                 'inline_create' => [ 'entity' => 'tag' ],

                // optional
                'entity'    => 'tags', // the method that defines the relationship in your Model
                'model'     => "App\Models\Tag", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                 'select_all' => true, // show Select All and Clear buttons?
           
                // optional
                // 'options'   => (function ($query) {
                //     return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
                // }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           ],
    
            ); 

            CRUD::addField(
            
                [    // Select2Multiple = n-n relationship (with pivot table)
                    'label'     => "Categories",
                    'type'      => 'relationship',
                    'name'      => 'categories', // the method that defines the relationship in your Model
               
                //    'ajax'          => true,
                  'inline_create' => [ 'entity' => 'category' ],
                
                    // optional
                    'entity'    => 'categories', // the method that defines the relationship in your Model
                    'model'     => "App\Models\Category", // foreign key model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                     'select_all' => true, // show Select All and Clear buttons?
               
                    // optional
                    // 'options'   => (function ($query) {
                    //     return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
                    // }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
               ],
        
                ); 
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
