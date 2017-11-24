<?php

namespace App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Employee
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Employee[] $children
 * @property-read \App\Employee $head
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $position
 * @property string $employmentDate
 * @property float $salary
 * @property int|null $head_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $thumbnail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereEmploymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereHeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Employee whereUpdatedAt($value)
 */
class Employee extends Model
{
    protected $fillable = ['name', 'employmentDate','salary','position','head_id','avatar','thumbnail'];
    public function isSupreme($empl){//возвращает true - если $empl является непосредственным или косвенным начальником
        $curHead=$empl;
        while($curHead=$curHead->head()->first()){
            if($curHead->id==$this->id){
                return true;
            }
        }
        return false;
    }
    public function changeHead($head_id){
        if($head=Employee::find($head_id)){
            if($this->isSupreme($head)){
                throw new Exception('parent');
            }else{
                $this->head_id=$head_id;
                $this->save();
                return true;
            }
        }
        return false;
    }
    public static function boot() {
        parent::boot();


        self::deleting(function ($value) {
            Employee::where('head_id',$value->id)->update(['head_id' => $value->head_id]);
        });
    }

    public function getSalaryAttribute($value)
    {
        return number_format($value,'2','.', ' ');
    }

    public function head()
    {
        return $this->belongsTo(Employee::class);
    }
    public function children()
    {
        return $this->hasMany(Employee::class,'head_id')->select(['id','name','position','head_id','employmentDate','salary']);
    }
}
