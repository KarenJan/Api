<?php
namespace Application\Models;

use Framework\Core\Mvc\Model\MysqlFields;
use Framework\Core\Mvc\Model\MysqlDataMaper;
use Framework\Api;

class Users extends MysqlDataMaper
{
	const USERS_DISABLED = 0;
	const USERS_ENABLED = 1;
	
	const USERS_FILE_PROFILE_PIC_PATH = 'userProfile';
	const USERS_FILE_PROFILE_PIC_DEFAULT_MALE_200 = 'default/users/200x200/man.jpg';
	const USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_200 = 'default/users/200x200/woman.jpg';
	const USERS_FILE_PROFILE_PIC_DEFAULT_MALE_40 = 'default/users/40x40/man.jpg';
	const USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_40 = 'default/users/40x40/woman.jpg';
	
	public function __construct(){
		$field = new MysqlFields($this->table(), 'id', MysqlDataMaper::FIELD_TYPE_INT);
		$field->pk(true);
		$field->noinsert(true);
		$field->noupdate(true);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'fbOAuthID', MysqlDataMaper::FIELD_TYPE_STR);
        $field->noupdate(true);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'email', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'emailVerify', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'username', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'displayName', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'password', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'hash', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'hashDate', MysqlDataMaper::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'language', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'enabled', MysqlDataMaper::FIELD_TYPE_INT);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'creationDate', MysqlDataMaper::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'creationIp', MysqlDataMaper::FIELD_TYPE_INT);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'modifiedDate', MysqlDataMaper::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'lastLoginDate', MysqlDataMaper::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new MysqlFields($this->table(), 'lastLoginIp', MysqlDataMaper::FIELD_TYPE_INT);
        $this->addField($field);
        /* $field = new MysqlFields('usersInfo', 'birthday', MysqlDataMaper::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new MysqlFields('usersInfo', 'mobilePhone', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields('usersInfo', 'website', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new MysqlFields('usersInfo', 'path', MysqlDataMaper::FIELD_TYPE_STR);
        $this->addField($field);
        $this->addJoin('usersInfo', 'left', 'usersInfo ON users.id = usersInfo.userID');
        */
	}
	/**
	 * @return string the associated database table name
	 */
	public static function model($className=__CLASS__)
	{
		return parent::record($className);
	}
	/**
	 * @return string the associated database table name
	 */
	public function table()
	{
		return 'users';
	}
	
	
	/**
	 * @return string
	 */
	public function getProfilePic($userID, $width = false){
		$fileSystem = Api::app()->getObjectManager()->get('fileSystem');
        $fileStorageModel = FileStorage::model();
        $profilePics = $fileStorageModel->where(array('entityType' => Entities::ENTITY_TYPE_USER, 
                                                      'entityID' => $userID,
                                                      'fileName' => 'profilePic'))
                                        ->limit(1)->run();
        if(!empty($profilePics)){
			if($width)
                return $fileSystem->convert(Api::app()->baseUrl . 
                        DS . 'file' . DS . self::USERS_FILE_PROFILE_PIC_PATH . DS . $userID . DS . $profilePics[0]->fileName . '.' . $profilePics[0]->fileExt, $width);
            return $fileSystem->convert(Api::app()->baseUrl . DS . 'file' . DS . self::USERS_FILE_PROFILE_PIC_PATH . 
                    DS . $userID . DS . $profilePics[0]->fileName . '.' . $profilePics[0]->fileExt);
		}
        else{
			$usersInfoModel = UsersInfo::model();
			$userInfo = $usersInfoModel->findByPK($userID);
			if(is_null($userInfo))
				return false;
            
            if($width && $width <= 40){
                $forMale = self::USERS_FILE_PROFILE_PIC_DEFAULT_MALE_40;
                $forFemale = self::USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_40;
            }
            else{
                $forMale = self::USERS_FILE_PROFILE_PIC_DEFAULT_MALE_200;
                $forFemale = self::USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_200;
            }   
            
			if($userInfo->gender == UsersInfo::USERSINFO_GENDER_MALE)
				return Api::app()->resourceUrl . DS . 'media' . DS . $forMale;
			elseif($userInfo->gender == UsersInfo::USERSINFO_GENDER_FEMALE)
				return Api::app()->resourceUrl . DS . 'media' . DS . $forFemale;
		}	
	}
	
	/**
	 * @return array from User IDs
	 */
	public function getProfilePicsFromIDs($userIDs = array(), $width = false){
		if(empty($userIDs))
            return false;
        $fileSystem = Api::app()->getObjectManager()->get('fileSystem');
        $fileStorageModel = FileStorage::model();
        $existsProfilePics = $fileStorageModel->where(array('entityType' => Entities::ENTITY_TYPE_USER, 
                                                      'fileName' => 'profilePic'))
                                                      ->in('entityID', $userIDs)->run();
        $profilePics = array();
        foreach($existsProfilePics as $value)
            $profilePics[$value->entityID] = $value;

        $photos = array();
        if(sizeof($profilePics) != sizeof($userIDs)){
            $usersInfoModel = UsersInfo::model();
            $usersInfo = $usersInfoModel->beginAllInArray('userID', $userIDs);

            foreach($usersInfo as $key => $value){
                if(isset($profilePics[$key])){
                    if($width){
                        $photos[$key] = $fileSystem->convert(Api::app()->baseUrl . DS . 'file' . 
                                                             DS . self::USERS_FILE_PROFILE_PIC_PATH . DS . $key . DS . 
                                                             $profilePics[$key]->fileName . '.' . $profilePics[$key]->fileExt, $width);
                    }
                    else{
                        $photos[$key] = $fileSystem->convert(Api::app()->baseUrl . DS . 'file' . 
                                                             DS . self::USERS_FILE_PROFILE_PIC_PATH . DS . $key . DS .
                                                             $profilePics[$key]->fileName . '.' . $profilePics[$key]->fileExt);
                    }
                }
                else{
                    if($width && $width <= 40){
                        $forMale = self::USERS_FILE_PROFILE_PIC_DEFAULT_MALE_40;
                        $forFemale = self::USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_40;
                    }
                    else{
                        $forMale = self::USERS_FILE_PROFILE_PIC_DEFAULT_MALE_200;
                        $forFemale = self::USERS_FILE_PROFILE_PIC_DEFAULT_FEMALE_200;
                    }   
                    if($value->gender == UsersInfo::USERSINFO_GENDER_MALE)
				        $photos[$key] = Api::app()->resourceUrl . DS . 'media' . DS . $forMale;
			        elseif($value->gender == UsersInfo::USERSINFO_GENDER_FEMALE)
				        $photos[$key] = Api::app()->resourceUrl . DS . 'media' . DS . $forFemale;
                }
            }
        }
        else{
            foreach($usersInfo as $key => $value){
                if($width){
                    $photos[$key] = $fileSystem->convert(Api::app()->baseUrl . DS . 'file' . 
                                                         DS . self::USERS_FILE_PROFILE_PIC_PATH . DS . $key . DS .
                                                         $profilePics[$key]->fileName . '.' . $profilePics[$key]->fileExt, $width);
                }
                else{
                    $photos[$key] = $fileSystem->convert(Api::app()->baseUrl . DS . 'file' . 
                                                         DS . self::USERS_FILE_PROFILE_PIC_PATH . DS . $key . DS .
                                                         $profilePics[$key]->fileName . '.' . $profilePics[$key]->fileExt);
                }
            }
        }
        return $photos;
	}
	
	
	
	
}
