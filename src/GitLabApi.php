<?php

namespace fenglangyj\GitLabApi;

use GuzzleHttp\Exception\GuzzleException;

class GitLabApi implements GitLabApiInterface
{
    /** @var array Default request options */
    private $config = [];
    /**
     * @var \GuzzleHttp\Client
     */
    private $Client;


    public function __construct(string $access_token, string $gitlab_url)
    {
        $this->config['access_token'] = $access_token;
        $this->config['gitlab_url'] = $gitlab_url;
        $this->Client = new \GuzzleHttp\Client([
            'base_uri' => $gitlab_url,
            'timeout'  => 2.0,
            'headers' => [
                //'Content-Type'  => 'application/x-www-form-urlencoded',
                'cache-control'=> 'no-cache',
                'PRIVATE-TOKEN' => $access_token,
            ]
        ]);
    }

    /**
     * 获取配置信息
     * @param null $option
     * @return array|mixed|null
     */
    public function getConfig($option = null)
    {
        return $option === null ? $this->config : ($this->config[$option] ?? null);
    }

    /**
     * 添加用户
     * curl -X POST -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users -H 'cache-control: no-cache' -H 'content-type: application/json' \
     * -d '{ "email": "liumiaocn@outlook.com", "username": "liumiao", "password": "12341234", "name": "liumiao","skip_confirmation": "true" }'
     */
    public function users_add($username,$email,$password)
    {
        $response = $this->Client->request('POST','/api/v4/users', [
            'json' => [
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'name' => $username,
                'skip_confirmation' => true
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * 查询所有用户
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users
     */
    public function users_all(){
        $response = $this->Client->request('GET','/api/v4/users');
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    /**
     * 查询单个用户
     * 指定某一参数进行查询，比如此处使用username进行查询
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users?username=liumiao
     * @return mixed
     * @throws GuzzleException
     */
    public function users_get($username){
        $response = $this->Client->request('GET','/api/v4/users?username='.$username);
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    /**
     * 修改用户信息
     * 比如这里使用PUT方法修改用户的密码信息，执行示例日志如下所示
     * userid=8
     * curl -X PUT -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users/${userid} \
     * -H 'cache-control: no-cache' \
     * -H 'content-type: application/json' \
     * -d '{ "password": "1234512345"}'
     * @param int $user_id
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function users_put(int $user_id,$data){
        $response = $this->Client->request('PUT','/api/v4/users/'.$user_id,[
            'json'=>$data
        ]);
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    /**
     * 删除用户
     * 删除前所有用户查询，获取用户ID
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users
     *
     * 删除用户liumiao
     * userid=9
     * curl -X DELETE -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/users/${userid}?hard_delete=true
     * @param int $user_id
     * @return mixed
     * @throws GuzzleException
     */
    public function users_del(int $user_id){
        $response = $this->Client->request('DELETE',"/api/v4/users/{$user_id}?hard_delete=true");
        return json_decode($response->getBody(), true);
    }

    /**
     * 建立项目：
     * namespace_id=16是后台添加的group:cloud，不设置的话，项目建立在当前用户下
     * curl -X POST -H "PRIVATE-TOKEN: ${access_token}" ${gitlab_url}/api/v4/projects -H 'cache-control: no-cache' -H 'content-type: application/json' \
     * -d '{ "name": "123123", "path": "123123","namespace_id":"16","initialize_with_readme":"true" }'
     * @param $name
     * @param $path
     * @param $namespace_id
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_add($name,$path,$namespace_id){
        $response = $this->Client->request('POST',"/api/v4/projects",[
            'json'=>[
                'name'=>$name,//项目名称
                'path'=>$path,//项目访问地址
                'namespace_id'=>$namespace_id,//所属分组
                'initialize_with_readme'=>false,//使用readme初始化项目？
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * 删除项目：
     * projectid=33
     * curl -X DELETE -H "PRIVATE-TOKEN: ${access_token}" "${gitlab_url}/api/v4/projects/${projectid}"
     * echo $?
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_del($project_id){
        $response = $this->Client->request('DELETE',"/api/v4/projects/{$project_id}");
        return json_decode($response->getBody(), true);
    }

    /**
     * 显示项目
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}" -H "simple=true" ${gitlab_url}/api/v4/projects
     * 显示单个项目：
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}"  "${gitlab_url}/api/v4/projects/1"
     * @param string $project_id 如果传入项目id，返回单个项目信息，如果留空，返回所有项目。
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_get(string $project_id=''){
        $response = $this->Client->request('GET',"/api/v4/projects/{$project_id}");
        return json_decode($response->getBody(), true);
    }

    /**
     * 显示项目用户
     * curl -X GET -H "PRIVATE-TOKEN: ${access_token}"  "${gitlab_url}/api/v4/projects/1/users"
     * @param string $project_id
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_users($project_id){
        $response = $this->Client->request('GET',"/api/v4/projects/{$project_id}/users");
        return json_decode($response->getBody(), true);
    }

    /**
     * 显示项目的成员：
     * curl --header "PRIVATE-TOKEN:  ${access_token}" "${gitlab_url}/api/v4/projects/${projectid}/members/all"
     * @param string $project_id
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_members_all($project_id){
        $response = $this->Client->request('GET',"/api/v4/projects/{$project_id}/members/all");
        return json_decode($response->getBody(), true);
    }

    /**
     * 添加一个成员到项目：
     * projectid=33
     * user_id=9
     * curl --request POST --header "PRIVATE-TOKEN: ${access_token}" --data "user_id=${user_id}&access_level=40" "${gitlab_url}/api/v4/projects/${projectid}/members"
     * @param $project_id
     * @param $user_id
     * @param int $access_level
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_members_add($project_id, $user_id, int $access_level=40){
        $response = $this->Client->request('POST',"/api/v4/projects/{$project_id}/members",[
            'json'=>[
                'user_id'=>$user_id,
                'access_level'=>$access_level,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * 列出存储库分支
     * @param int $project_id 项目id
     * @param string $search 搜索分支的关键字，您可以使用 ^term 或者 term$ 来搜索以term开头和以term结尾的分支
     * @return mixed
     */
    public function projects_branches_get(int $project_id, string $search = '') {
        $response = $this->Client->request('GET', "/api/v4/projects/{$project_id}/repository/branches", [
            'query'=>[
                'search'=>$search,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * 创建存储库分支
     * @param int $project_id 项目id
     * @param string $branch 分支的名称。
     * @param string $ref 分支名称或来源分支名
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_branches_add(int $project_id, string $branch ,string $ref) {
        $response = $this->Client->request('POST', "/api/v4/projects/{$project_id}/repository/branches", [
            'json'=>[
                'branch'=>$branch,
                'ref'=>$ref,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * 删除存储库分支(如果出现异常，会抛出异常)
     * @param int $project_id
     * @param string $branch
     * @return mixed
     * @throws GuzzleException
     */
    public function projects_branches_del(int $project_id, string $branch) {
        $response = $this->Client->request('DELETE', "/api/v4/projects/{$project_id}/repository/branches/{$branch}");
        return json_decode($response->getBody(), true);
    }

}
