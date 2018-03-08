<?php
/**
 * @author bean
 * @version: 1.0
 */
namespace app\models;

use newx\orm\base\Model;

class UserModel extends Model
{
    public $table = 'user';

    public $db = 'default';
}