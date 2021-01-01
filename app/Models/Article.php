<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


/**
 * @SWG\Definition(
 *      definition="Article",
 *      required={"title", "content", "status_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status_name",
 *          description="status name",
 *          type="string",
 *         enum={"draft", "publish", "schedule"}
 *      ),
 *      @SWG\Property(
 *          property="tags",
 *          description="tags",
 *          type="array",
 *         @SWG\Items(
 *                      ref="#/definitions/Tag"            
 *                      ),
 *       example={"ball" , "cool"}
 *              
 *          
 *      ),
 *      @SWG\Property(
 *          property="categories",
 *          description="categories",
 *          type="array",
 *         @SWG\Items(
 *                      ref="#/definitions/Category"            
 *                      ),
 *          example={"sport" }
 *         
 *          
 *      ),
 * )
 */
class Article extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;


    public $table = 'articles';
    



    public $fillable = [
        'title',
        'content',
        'image',
        'status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'image' => 'string',
        'status_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'content' => 'required',
        'image' => 'nullable',
       // 'status_name' => 'required',
        'status_name' => 'exists:App\Models\Status,name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class , 'articles_categories');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tags()
    {
        return $this->belongsToMany(\App\Models\Tag::class ,'articles_tags');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }

    public function setimageAttribute($value)
    {
        $attribute_name = "image";
        $disk = config('backpack.base.root_disk_name'); 
        $destination_path = "storage/app/images"; 

        // if the image was erased
        if ($value==null) {
      
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $this->attributes[$attribute_name] = '/images/award.png';
        }

        if (starts_with($value, 'data:image'))
        {
            $image = \Image::make($value)->encode('jpg', 90);
            $filename = md5($value.time()).'.jpg';
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = '/images/'.$filename;

        }
    }
}
