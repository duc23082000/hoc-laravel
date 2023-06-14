<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportNotice extends Model
{
    use HasFactory;
    protected $table = 'import_notice';
    public $timestamps = true;
    protected $fillable = ['name', 'status', 'notification', 'user_id', 'modified_by_id'];
    protected $attribute = ['status_name', 'status_color'];

    public function getStatusNameAttribute(){
        return $this->status == 0 ? 'Success' : 'Fail';
    }

    public function getStatusColorAttribute(){
        return $this->status == 0 ? 'primary' : 'warning';
    }
}
