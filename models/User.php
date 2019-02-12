<?php
/**
 * User: SeaReef
 * Date: 2018/6/28 20:46
 */
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function rules()
    {
        return [
            [['username','email','role','password_hash','auth_key','status'],'required'],
            ['email','email'],
            ['username','string'],
            [['created_at','updated_at'],'safe'],
        ];
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * 返回对应玩家的展示菜单
     */
    public static function getMenu($uid)
    {
//        获取角色名称
        $item_name = (new Query())->select('item_name')->from('auth_assignment')->where(['user_id' => $uid])->scalar();
        if (!$item_name) {
            return [];
        } else {
//        获取对应权限
            $child = (new Query())->select('child')->from('auth_item_child')->where(['parent' => $item_name])->column();

//        一级菜单权限
            foreach ($child as $v) {
                $item_list = (new Query)->select('item_name')->from('auth_assignment')->column();
                $p = (new Query())->select('parent')->from('auth_item_child')->where(["child" => $v])->andWhere(['not in', 'parent', $item_list])->scalar();
                $data[$p][] = $v;
            }

//            二级菜单列表
            foreach ($data as $k => $v) {
                foreach ($v as $kk => $vv) {
                    $info = (new Query())->select('description')->from('auth_item')->where(['name' => $vv, 'state' => 1])->scalar();
                    unset($data[$k][$kk]);

                    if (!$info) {
                        continue;
                    }

                    $data[$k][] = [
                        'name' => $info,
                        'url' => $vv,
                    ];
                }
            }
        }

        return $data;
    }
}



















