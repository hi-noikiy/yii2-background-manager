<?php
/**
 * User: jw
 * Date: 2018/9/6 0006
 */
namespace app\controllers;

use Yii;
use app\controllers\CommonController;
use yii\db\Query;
use app\models\User;

class AuthController extends CommonController
{
    public $enableCsrfValidation = false;

//    初始化操作
    public function init()
    {
        if (empty(Yii::$app->user->id)) {
            $this->redirect('/user/login');
        }
    }
    /**
     * 角色页面
     */
    public function actionRoleIndex()
    {
        return $this->render('auth_role');
    }

    /**
     * 管理员列表
     */
    public function actionManagerIndex()
    {
        return $this->render('manager_list');
    }

    /**
     * 权限规则页面
     */
    public function actionRuleIndex()
    {
        return $this->render('rule');
    }

    /**
     * 权限规则管理页面
     */
    public function actionRuleManageIndex()
    {
        return $this->render('rule_manage');
    }

    /**
     * 添加角色
     */
    public function actionAddRole()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        if (!$request['role'] || !$request['category'] || !$request['permission']) {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
        $role = $auth->createRole($request['role']);
        $role->description = $request['desc'];
        $auth->add($role);

        foreach ($request['permission'] as $key => $val) {
            $permission = $auth->createPermission($val);
            $permission->state = 1;

            $auth->addChild($role,$permission);
        }
        return $this->writeResult(self::CODE_OK);
        /*foreach ($request['category'] as $key => $val) {
            $category = $auth->createPermission($val);
            $category->type = 3;
            $auth->addChild($role,$category);
        }*/
    }

    /**
     * 更新角色
     */
    public function actionUpdateRole()
    {
        $request = Yii::$app->request->post();
        if (!$request['role'] || !$request['category'] || !$request['permission']) {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($request['role']);
        $auth->removeChildren($role);
        foreach ($request['permission'] as $key => $val) {
            $permission = $auth->createPermission($val);

            $permission->description = '权限分类';
            $permission->type = 3;
            $permission->state = 1;

            $auth->addChild($role,$permission);
        }
        return $this->writeResult(self::CODE_OK);
    }

    /**
     * 删除角色
     */
    public function actionDelRole()
    {
        //TODO::有用户已分配是否可删除
        $request = Yii::$app->request->post();
        /*$is_used = (new Query())
            ->select('*')
            ->from('auth_assignment')
            ->where('item_name = "'.$request['role'].'"')
            ->one();
        if ($is_used) {//是否已被使用
            return $this->writeResult(self::CODE_ROLE_USED);
        }*/
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($request['role']);
        $result = $auth->remove($role);
        if ($result) {
            return $this->writeResult(self::CODE_OK);
        } else {
            return $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 角色列表(无分页)
     */
    public function actionRoleList()
    {
        $auth = Yii::$app->authManager;
        $rows = (new Query())
            ->select('*')
            ->from('auth_item')
            ->where('type = 1')
            ->orderBy('created_at')
            ->all();
        return $this->writeJson(1,self::CODE_OK,'',count($rows),$rows?$rows:[]);

    }

    /**
     * 角色列表(有分页)
     */
    public function actionRoleListPage()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('auth_item')
            ->where('type = 1')
            ->orderBy('created_at')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        return $this->writeJson(1,self::CODE_OK,'',count($rows),$rows?$rows:[]);
    }



    /**
     * 角色详情
     * 包含角色关联权限
     */
    public function actionRoleDetail()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;

        if (isset($request['role']) && $request['role']) {
            $role = $auth->getRole($request['role']);
            $rows = $auth->getPermissionsByRole($request['role']);
            return $this->writeJson(1,self::CODE_OK,'',count($rows),$rows?array_keys($rows):[]);
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }

    }

    /**
     * 添加权限分类
     */
    public function actionAddPermissionCategory()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $db = Yii::$app->db;

        if (isset($request['category']) && $request['category']) {
            $category = $db->createCommand('select * from auth_item where type = 3 and name = "'.$request['category'].'"')->queryOne();
            if ($category) {//已存在分类
                return $this->writeJson(2,self::CODE_AUTH_CATEGORY_EXISTS);
            }
            $permission = $auth->createPermission($request['category']);
            $permission->description = '权限分类';
            $permission->type = 3;
            $permission->state = 1;
            $result = $auth->add($permission);
            if ($result) {
                return $this->writeJson(2,self::CODE_OK);
            } else {
                return $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 权限分类列表
     */
    public function actionPermissionCategoryList()
    {
        $page = Yii::$app->request->get('page');
        $limit = Yii::$app->request->get('limit');
        $rows = (new Query())
            ->select('*')
            ->from('auth_item')
            ->where('type = 3')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();

        $count = (new Query())
            ->select('*')
            ->from('auth_item')
            ->where('type = 3')
            ->count();
        return $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 更新权限分类
     */
    public function actionUpdatePermissionCategory()
    {
        $request = Yii::$app->request->post();
        $db = Yii::$app->db;
        if (isset($request['category']) && $request['category']) {
            $auth = Yii::$app->authManager;
            $category = $auth->createPermission($request['category']);
            $category->type = 3;
            if (isset($request['old_category']) && $request['old_category']) {
                $category = $db->createCommand('select * from auth_item where type = 3 and name = "'.$request['old_category'].'"')->queryOne();
                if (!$category) {//分类是否存在
                    $result = $auth->add($category);
                } else {
                    $result = $db->createCommand()->update('auth_item',['name'=>$request['category']],'name = "'.$request['old_category'].'" and type = 3')->execute();
                }
            }

            if ($result) {
                return $this->writeResult(self::CODE_OK);
            } else {
                return $this->writeResult(self::CODE_ERROR);
            }
        } else {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }


    }

    /**
     * 增加权限(有分类)
     */
    public function actionAddPermission()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $db = Yii::$app->db;
        if (isset($request['category']) && $request['category'] && isset($request['permission']) && $request['permission'] && isset($request['desc']) && $request['desc']) {
            $category = $db->createCommand('select * from auth_item where type = 3 and name = "'.$request['category'].'"')->queryOne();
            if (!$category) {//分类是否存在
                return $this->writeJson(2,self::CODE_AUTH_CATEGORY_NOT_FOUND);
            }
            $category = $auth->createPermission($request['category']);
            $category->type = 3;
            $permission = $auth->createPermission($request['permission']);
            $permission->description = $request['desc'];
            $permission->state = $request['type'];
            $auth->add($permission);
            $result = $auth->addChild($category,$permission);
            if ($result) {
                return $this->writeJson(2,self::CODE_OK);
            } else {
                return $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }

    }

    /**
     *更新权限
     */
    public function actionUpdatePermission()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;

        if (isset($request['old_permission']) && $request['old_permission'] && isset($request['category']) && $request['category'] && isset($request['permission']) && $request['permission'] && isset($request['desc']) && $request['desc']) {
            $permission = $auth->createPermission($request['permission']);
            $permission->description = $request['desc'];
            $result = $auth->update($request['old_permission'],$permission);
            if ($result) {
                return $this->writeJson(2,self::CODE_OK);
            } else {
                return $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 删除权限(分类和权限关系也被删除)
     */
    public function actionDelPermission()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;

        if (isset($request['permission']) && $request['permission']) {
            $permission = $auth->createPermission($request['permission']);
            $result = $auth->remove($permission);
            if ($result) {
                return $this->writeJson(2,self::CODE_OK);
            } else {
                return $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 权限列表(展示每个权限的分类)
     */
    public function actionPermissionList()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        $auth = Yii::$app->authManager;
        $rows = (new Query())
            ->select('a.name,a.description,c.name as parent')
            ->from('auth_item as a')
            ->rightJoin('auth_item_child as b','a.name = b.child')
            ->rightJoin('auth_item as c','c.name = b.parent')
            ->where('a.type = 2 and c.type = 3')
            ->orderBy('c.created_at')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('a.name,c.name')
            ->from('auth_item as a')
            ->rightJoin('auth_item_child as b','a.name = b.child')
            ->rightJoin('auth_item as c','c.name = b.parent')
            ->where('a.type = 2 and c.type = 3')
            ->count();
        return $this->writeJson(1,0,'',$count,$rows?$rows:[]);

    }

    /**
     * 分类下的权限列表
     */
    public function actionPermissionListByCate()
    {
        $auth = Yii::$app->authManager;
        $rows = (new Query())
            ->select('*')
            ->from('auth_item')
            ->where('type = 3')
            ->all();
        foreach ($rows as $key=>$val) {
            $child = (new Query())
                ->select('*')
                ->from('auth_item_child')
                ->where('parent = "'.$val['name'].'"')
                ->all();
            if ($child) {
                foreach ($child as $k => $v) {
                    $rows[$key]['child'][$k]['name'] = $v['child'];
                    $permission = $auth->getPermission($v['child']);
                    $rows[$key]['child'][$k]['desc'] = $permission->description;
                }
            } else {
                $rows[$key]['child'] = [];

            }

            //$rows[$key]['child'] = array_column($child,'child');
            /*if (!$child) {
                unset($rows[$key]);
            }*/
        }
        return $this->writeJson(1,0,'',count($rows),$rows?$rows:[]);

    }

    /**
     * 删除权限分类（关联外键会将下面权限删除）
     */
    public function actionDelPermissionCategory()
    {
        $request = Yii::$app->request->get();
        $result = Yii::$app->db->createCommand()->delete('auth_item','name = "'.$request['category'].'" and type = 3')->execute();
        if ($result) {
            return $this->writeJson(2,self::CODE_OK);
        } else {
            return $this->writeJson(2,self::CODE_ERROR);
        }
    }
    /**
     * 为角色添加权限
     */
    public function actionAddRule()
    {
        $request = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $request = [
            'role' => 'aaa',
            'rule' => 'wer',
        ];
        $role = $auth->createRole($request['role']);
        $permission = $auth->createRole($request['rule']);
        $result = $auth->addChild($role,$permission);
        if ($result) {
            $this->writeResult(self::CODE_OK);
        } else {
            $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 用户分配角色
     */
    public function actionAssignRole()
    {
        $request = Yii::$app->request->post();
        $request = [
            'role' => 'aaaa',
            'user' => 1
        ];
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($request['role']);
        $result = $auth->assign($role,$request['user']);
        if ($result) {
            $this->writeResult(self::CODE_OK);
        } else {
            $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 后台管理员账号添加
     */
    public function actionCreateManager()
    {
        $request = Yii::$app->request->post();
        $model = new User();
        if (!isset($request['username']) || !$request['username'] || !isset($request['password']) || !$request['password'] || !isset($request['email']) || !$request['email'] || !isset($request['role']) || !$request['role'] ) {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }

        //var_dump($model);exit;
        $is_exists = (new Query())
            ->select('*')
            ->from('auth_user')
            ->where('username = "'.$request['username'].'"')
            ->one();
        if ($is_exists) {
            return $this->writeJson(2,self::CODE_USER_EXISTS);
        }
        $request['password_hash'] = Yii::$app->getSecurity()->generatePasswordHash($request['password']);
        $request['auth_key'] = Yii::$app->getSecurity()->generateRandomString();
        $request['status'] = 1;
        $request['created_at'] = time();
        $request['updated_at'] = time();
        if ($model->load($request,'') && $model->save()) {
            $auth = Yii::$app->authManager;

            foreach ($model->role as $key => $val) {
                $role = $auth->createRole($val);
                $auth->assign($role,$model->id);
            }
            return $this->writeJson(2,self::CODE_OK);
        } else {
            var_dump($model->getErrors());exit;
        }
        return $this->writeJson(2,self::CODE_ERROR);
    }

    /**
     * 后台管理员列表
     */
    public function actionManagerList()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        $where = [];
        if (isset($request['start_time']) && $request['start_time']) {
            $where[] = ' created_at >= '.strtotime($request['start_time']);
        }
        if (isset($request['end_time']) && $request['end_time']) {
            $where[] = ' created_at <= '.strtotime($request['end_time']);
        }
        if (isset($request['username']) && $request['username']) {
            $where[] = ' username like "%'.$request['username'].'%"';
        }
        $where = implode(' and ',$where);
        $rows = (new Query())
            ->select('*')
            ->from('auth_user')
            ->where($where)
            ->orderBy('created_at desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $rows = array_map(function($data){
            $data['created_at'] = date('Y-m-d H:i:s',$data['created_at']);
            return $data;
        },$rows);
        $auth = Yii::$app->authManager;
        foreach ($rows as $key=>$val) {
            $rows[$key]['roles'] = array_keys($auth->getRolesByUser($val['id']));
        }

        return $this->writeJson(1,0,'',count($rows),$rows?$rows:[]);
    }

    /**
     * 更新后台管理员
     */
    public function actionUpdateManager()
    {
        $request = Yii::$app->request->post();

        if (isset($request['id']) && $request['id']) {
            $model = User::findOne($request['id']);
            $model->updated_at = time();
            $auth = Yii::$app->authManager;


            if ($model->load($request,'') && $model->save()) {
                $auth->revokeAll($request['id']);


                    foreach ($model->role as $key => $val) {
                        $role = $auth->createRole($val);
                        $auth->assign($role,$model->id);

                    $this->writeJson(2,self::CODE_OK);
                }
            } else {
//                var_dump($model->getErrors());exit;
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            echo 1;
        }
    }

    public function actionDelManager()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = User::findOne($request['id']);
            $result = $model->delete();
            if ($result) {
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        }
    }

    /**
     * 管理员拥有权限
     */
    public function actionManagerRoles()
    {
        $auth = Yii::$app->authManager;
        $uid = Yii::$app->user->getId();
        if ($uid) {
            $model = User::findOne($uid);
            if ($model->username == 'admin') {//超级管理员
                return $this->writeJson(1,self::CODE_OK,'',0,1);
            }
            $permissions = $auth->getPermissionsByUser($uid);
            return $this->writeJson(1,self::CODE_OK,'',count($permissions),$permissions?array_keys($permissions):[]);
        } else {
            return $this->redirect('/user/login');
        }
    }
}