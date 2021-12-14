# gitlab接口调用sdk

## gitlab官方接口文档：

> https://docs.gitlab.com/ee/api/api_resources.html

## 创建 apitoken

登录gitlab后台,访问: “http://【您的gitlab域名】/profile/personal_access_tokens” 创建 apitoken

## 使用方法

  ```
    $access_token="【在您gitlab后台获取到的apitoken】";
    $gitlab_url="【您的gitlab访问域名】";
    $GitLabApi = new \fenglangyj\GitLabApi\GitLabApi($access_token,$gitlab_url);
    
    //获取配置信息
    $conf = $GitLabApi->getConfig();
    
    //添加账户
    $res = $GitLabApi->users_add('fenglangyj','fenglangyj@139.com','asdfasdf');
    print_r($res);

    //查询所有用户
    $user_all = $GitLabApi->users_all();
    print_r($user_all);

    //查询指定用户
    $user_info = $GitLabApi->users_get('username');
    print_r($user_info);
    
    //修改用户数据
    $user_info = $GitLabApi->users_put(2,[
        'password'=>'asdfasdf'
    ]);
    print_r($user_info);

    //删除用户数据
    $res = $GitLabApi->users_del(2);
    print_r($res);

    //建立项目，分组id3是米末分组
    $res = $GitLabApi->projects_add('projects_name2','projects_path2',3);
    print_r($res);
    
  ```
  



