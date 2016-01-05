<?php

namespace common\models;

use common\components\Json;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property integer $id
 * @property string  $module_name
 * @property string  $module_title
 * @property string  $module_description
 * @property string  $module_config
 * @property integer $module_status
 * @property string  $module_dir
 * @property integer $module_bb
 * @property integer $module_fb
 * @property string  $module_date
 * @property string  $module_modified
 */
class Module extends ActiveRecord
{
    // Constant for module activation.
    const MODULE_ACTIVE = 1;
    const MODULE_INACTIVE = 0;

    // Constant for module backend bootstrapping .
    const MODULE_BB = 1;
    const MODULE_BNB = 0;

    // Constant for module backend bootstrapping .
    const MODULE_FB = 1;
    const MODULE_FNB = 0;

    /**
     * @var UploadedFile
     */
    public $module_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_name', 'module_title', 'module_config', 'module_dir'], 'required'],
            [['module_title', 'module_description', 'module_config'], 'string'],
            [['module_name'], 'string', 'max' => 64],
            [['module_dir'], 'string', 'max' => 128],
            [['module_name', 'module_dir'], 'unique'],
            [['module_date', 'module_modified'], 'safe'],
            [['module_status', 'module_bb', 'module_fb'], 'integer'],
            [['module_status'], 'in', 'range' => [self::MODULE_ACTIVE, self::MODULE_INACTIVE]],
            [['module_status'], 'default', 'value' => self::MODULE_INACTIVE],
            [['module_bb'], 'in', 'range' => [self::MODULE_BB, self::MODULE_BNB]],
            [['module_bb'], 'default', 'value' => self::MODULE_BNB],
            [['module_bb'], 'in', 'range' => [self::MODULE_FB, self::MODULE_FNB]],
            [['module_bb'], 'default', 'value' => self::MODULE_FNB],
            [['module_file'], 'required', 'on' => 'create'],
            [['module_file'], 'file', 'extensions' => 'zip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => Yii::t('writesdown', 'ID'),
            'module_name'        => Yii::t('writesdown', 'Name'),
            'module_title'       => Yii::t('writesdown', 'Title'),
            'module_description' => Yii::t('writesdown', 'Description'),
            'module_config'      => Yii::t('writesdown', 'Config'),
            'module_status'      => Yii::t('writesdown', 'Active'),
            'module_dir'         => Yii::t('writesdown', 'Directory'),
            'module_fb'          => Yii::t('writesdown', 'Frontend Bootstrap'),
            'module_bb'          => Yii::t('writesdown', 'Backend Bootstrap'),
            'module_date'        => Yii::t('writesdown', 'Installed'),
            'module_modified'    => Yii::t('writesdown', 'Updated'),
            'module_file'        => Yii::t('writesdown', 'Module (ZIP)'),
        ];
    }

    /**
     * Get module status as array
     */
    public function getStatus()
    {
        return [
            self::MODULE_ACTIVE   => Yii::t('writesdown', 'Yes'),
            self::MODULE_INACTIVE => Yii::t('writesdown', 'No'),
        ];
    }

    /**
     * Get array
     */
    public function getBackendBootstrap()
    {
        return [
            self::MODULE_BB  => Yii::t('writesdown', 'Yes'),
            self::MODULE_BNB => Yii::t('writesdown', 'No'),
        ];
    }

    /**
     * Get array
     */
    public function getFrontendBootstrap()
    {
        return [
            self::MODULE_FB  => Yii::t('writesdown', 'Yes'),
            self::MODULE_FNB => Yii::t('writesdown', 'No'),
        ];
    }


    /**
     * Get active modules.
     *
     * @return array|Module[]
     */
    public static function getActiveModules()
    {
        return static::find()->where(['module_status' => 1])->all();
    }

    /**
     * Get config as array.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return Json::decode($this->module_config);
    }

    /**
     * Get module backend config.
     *
     * @return array
     */
    public function getBackendConfig()
    {
        $config = $this->getConfig();

        if (isset($config['backend'])) {
            return $config['backend'];
        }

        return [];
    }

    /**
     * Get module frontend config.
     *
     * @return array
     */
    public function getFrontendConfig()
    {
        $config = $this->getConfig();

        if (isset($config['frontend'])) {
            return $config['frontend'];
        }

        return [];
    }

    /**
     * Get module param path.
     *
     * @return string.
     */
    public function getParamPath()
    {
        return Yii::getAlias('@modules/' . $this->module_dir . '/config/params.php');
    }

    /**
     * Get module config path.
     *
     * @return string.
     */
    public function getConfigPath()
    {
        return Yii::getAlias('@modules/' . $this->module_dir . '/config/config.php');
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->module_date = new Expression('NOW()');
            }
            $this->module_modified = new Expression('NOW()');

            return true;
        }

        return false;
    }
}
