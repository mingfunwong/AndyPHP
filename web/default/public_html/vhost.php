<?php
$username = 'admin';
$password = 'admin';
switch (true) {
    case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
    case $_SERVER['PHP_AUTH_USER'] !== $username:
    case $_SERVER['PHP_AUTH_PW']   !== $password:
        header('WWW-Authenticate: Basic realm="Enter username and password."');
        header('Content-Type: text/plain; charset=utf-8');
        die('Enter username and password.');
}
define('WWW_DIR', substr($_SERVER['DOCUMENT_ROOT'], 0, -19));
define('ANDYPHP_DIR', substr($_SERVER['DOCUMENT_ROOT'], 0, -23));
define('VHOST_DIR', ANDYPHP_DIR . 'apache/conf/vhost/');
define('FTP_FILE',  ANDYPHP_DIR . 'ftp/FileZilla Server.xml');
define('MYSQL_HOSTNAME', 'localhost');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');
// error_reporting(0);
ajax();
?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>编辑虚拟主机</title>

    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>

  </head>
  <body>

    <div class="container">
      <div class="page-header">
        <h1><a href="<?php echo $_SERVER['PHP_SELF'] ?>">编辑虚拟主机</a></h1>
      </div>
      <?php echo action() ?>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="post">

        <br>
        <input type="checkbox" name="op[vhost]" value="vhost" checked> 虚拟主机：
        <div class="form-group">
          <label >
            名字
            <input type="text" class="form-control" value="1.lvh.me" name="vhost_domain">
          </label>
        </div>
        <div class="form-group">
          <label >
            绑定域名
            <input type="text" class="form-control" value="" name="vhost_alias" style="width: 410px">
          </label>
        </div>
        <div class="form-group">
          <label>
            目录
            <input type="text" class="form-control" value="1.lvh.me" name="vhost_dir">
          </label>
        </div>

        <br>
       <input type="checkbox" name="op[mysql]" value="mysql" checked>  MySQL：
        <div class="form-group">
          <label >
            账号
            <input type="text" class="form-control" value="" name="mysql_username">
          </label>
        </div>
        <div class="form-group">
          <label>
            密码
            <input type="text" class="form-control" value="" name="mysql_password">
          </label>
        </div>

        <br>
        <input type="checkbox" name="op[ftp]" value="ftp"> FTP：
        <div class="form-group">
          <label >
            账号
            <input type="text" class="form-control" value="" name="ftp_username">
          </label>
        </div>
        <div class="form-group">
          <label>
            密码
            <input type="text" class="form-control" value="" name="ftp_password">
          </label>
        <div class="form-group">
          <label>
            目录
            <input type="text" class="form-control" value="" name="ftp_dir">
          </label>
        </div>
        </div>

        <br>
        <button type="submit" class="btn btn-default">保存</button>
      </form>

      <br>
      <h3>
        虚拟主机列表
        <button type="submit" class="btn btn-default" onclick="javascript:ajax_restart('虚拟主机')">重启</button>
      </h3>
      <table class="table table-condensed">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="15%">名字</th>
          <th>绑定域名</th>
          <th>目录</th>
          <th width="15%">创建时间</th>
          <th width="15%">操作</th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0; foreach (vhost_list() as $key => $value) : $i++; ?>
        <tr>
          <th scope="row"><?php echo $i ?></th>
          <td><a href='http://<?php echo $value['domain'] ?>/' target='_blank'><?php echo $value['domain'] ?></a></td>
          <td><?php foreach (explode(" ", $value['alias']) as $val) {
            echo "<a href='http://{$val}' target='_blank'>{$val}</a> ";
          } ?></td>
          <td><?php echo WWW_DIR . $value['dir'] . '/public_html' ?></td>
          <td><?php echo $value['create_time'] ?></td>
          <td>
            <a href="javascript:if(confirm('是否要删除所选域名？'))window.location='?act=vhost_del&domain=<?php echo $key ?>'">删除</a>
            <a href="javascript:if(confirm('是否要删除所选域名？强制删除将会删除所有文件。'))window.location='?act=vhost_del&force=1&domain=<?php echo $key ?>'">删除并清空</a>
            <a href="#" onclick='javascript:$("input[name=vhost_domain]").val("<?php echo $value['domain'] ?>");$("input[name=vhost_alias]").val("<?php echo $value['alias'] ?>");$("input[name=vhost_dir]").val("<?php echo $value['dir'] ?>");scroll(0,0);'>修改</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      </table>

      <br>
      <h3>
        MySQL 列表
        <button type="submit" class="btn btn-default" onclick="javascript:ajax_restart('MySQL')">重启</button>
      </h3>
      <table class="table table-condensed">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="15%">账号名</th>
          <th>主机</th>
          <th>密码</th>
          <th>容量</th>
          <th width="15%">操作</th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0; foreach (mysql_list() as $key => $value) : $i++; ?>
        <tr>
          <th scope="row"><?php echo $i ?></th>
          <td><?php echo $value['User'] ?></td>
          <td><?php echo $value['Host'] ?></td>
          <td><?php echo $value['Password'] ? "有" : "无" ?></td>
          <td><?php echo mysql_count($value['User']) ?></td>
          <td>
            <a href="javascript:if(confirm('是否要删除所选MySQL？'))window.location='?act=mysql_del&user=<?php echo $value['User'] ?>&host=<?php echo $value['Host'] ?>'">删除</a>
            <a href="javascript:if(confirm('是否要删除所选MySQL？？强制删除将会删除所有数据。'))window.location='?act=mysql_del&force=1&user=<?php echo $value['User'] ?>&host=<?php echo $value['Host'] ?>'">删除并清空</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      </table>

      <br>
      <h3>FTP 列表
        <button type="submit" class="btn btn-default" onclick="javascript:ajax_restart('FTP')">重启</button>
      </h3>
      <table class="table table-condensed">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="15%">账号名</th>
          <th>目录</th>
          <th>创建时间</th>
          <th width="15%">操作</th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0; foreach (ftp_list() as $key => $value) : $i++; ?>
        <tr>
          <th scope="row"><?php echo $i ?></th>
          <td><?php echo $value['username'] ?></td>
          <td><?php echo $value['dir'] ?></td>
          <td><?php echo date("Y-m-d H:i", filectime($value['dir'])) ?></td>
          <td>
            <a href="javascript:if(confirm('是否要删除所选FTP？'))window.location='?act=ftp_del&id=<?php echo $key ?>'">删除</a>
            <a href="javascript:if(confirm('是否要删除所选FTP？强制删除将会删除所有文件。'))window.location='?act=ftp_del&force=1&id=<?php echo $key ?>'">删除并清空</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      </table>

    </div>
  </body>
</html>
<?php
function action() {
  // 新增虚拟主机
  echo op_vhost_add();
  // 删除虚拟主机
  echo op_vhost_del();

  // 新增 MySQL
  echo op_mysql_add();
  // 删除 MySQL
  echo op_mysql_del();

  // 新增 FTP
  echo op_ftp_add();
  // 删除 FTP
  echo op_ftp_del();

}


// 获取虚拟主机
function vhost_list() {
  $items = array();
  $dh=opendir(VHOST_DIR);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=VHOST_DIR."/".$file;
      $content = file_get_contents($fullpath);
      preg_match("/ServerName (.*)/", $content, $domain);
      $domain = from($domain, 1);
      preg_match("/ServerAlias (.*)/", $content, $alias);
      $alias = from($alias, 1);
      preg_match("/DocumentRoot (.*)/", $content, $dir);
      $dir = from($dir, 1);
      $dir = str_replace('../web/', '', $dir);
      $dir = str_replace('/public_html', '', $dir);
      $items[$file] = array(
        'domain' => ($domain) ? $domain : "localhost",
        'alias' => $alias,
        'dir' => trim($dir),
        'create_time' => date('Y-m-d H:i:s', filectime($fullpath))
      );
    }
  }
  $items = multi_array_sort($items, 'create_time');

  return $items;
}

// 增加虚拟主机
function op_vhost_add() {
  if (in_array('vhost', from($_POST, 'op', array()))) {
    $domain = from($_POST, 'vhost_domain');
    $alias = from($_POST, 'vhost_alias');
    $dir = from($_POST, 'vhost_dir');
    if (!$domain) return "<div class='alert alert-danger'>请填写域名</div>";
    if (!$dir) return "<div class='alert alert-danger'>请填写目录</div>";
    $list = vhost_list();
    $dir1 = WWW_DIR . $dir;
    $dir2 = $dir1 . '/public_html';
    $file = "{$domain}.conf";
    $fullpath = VHOST_DIR . $file;
    $content = '<VirtualHost *:801>\nDocumentRoot ../web/'.$dir.'/public_html\nServerName '.$domain.'\nServerAlias '.$alias.'\nphp_admin_value open_basedir '.WWW_DIR.$dir.';C:/Windows/TEMP;'.ANDYPHP_DIR.'temp\n<IfModule mod_deflate.c>\nDeflateCompressionLevel 7\nAddOutputFilterByType DEFLATE text/html text/plain text/xml application/x-httpd-php\nAddOutputFilter DEFLATE css js html htm gif jpg png bmp php\n</IfModule>\n</VirtualHost>\n<Directory ../web/'.$dir.'>\n    Options FollowSymLinks\n  DirectoryIndex index.php index.html\n    AllowOverride All\n    Order allow,deny\n    Allow from all\n</Directory>\n';
    $content = str_replace('\n', "\n", $content);
    $old_content = (file_exists($fullpath)) ? file_get_contents($fullpath) : "";
    if ($old_content != $content) {
      file_put_contents($fullpath, $content);
      !file_exists($dir1) && mkdir($dir1);
      !file_exists($dir2) && mkdir($dir2);
      // $index_file = "{$dir2}/index.html";
      // !file_exists($index_file) && file_put_contents($index_file, "<meta charset='utf-8'>虚拟主机创建成功！域名：{$domain}");
    }
    return "<div class='alert alert-success'>虚拟主机增加成功，重启主机后生效，点击 <a href='javascript:ajax_restart(\"虚拟主机\");' >重启虚拟主机</a>。点击访问 <a href='http://{$domain}/' target='_blank'>http://{$domain}/</a></div>";
  }
}

// 删除虚拟主机
function op_vhost_del() {
  if (from($_GET, 'act') == 'vhost_del') {
    $domain = from($_GET, 'domain');
    $force = from($_GET, 'force');
    $list = vhost_list();
    if ($domain == '00000.default.conf') {
      return "<div class='alert alert-danger'>系统虚拟主机不能删除</div>"; 
    }
    if (isset($list[$domain])) {
      if ($force) {
        $fullpath = WWW_DIR . $list[$domain]['dir'];
        deldir($fullpath);
      }
      unlink(VHOST_DIR . $domain);
    }
    return "<div class='alert alert-success'>域名删除成功，重启主机后生效，点击 <a href='javascript:ajax_restart(\"虚拟主机\");' >重启虚拟主机</a></div>";
  }
}

// 删除目录
function deldir($dir) {
  //先删除目录下的文件：
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
 
  closedir($dh);
  //删除当前文件夹：
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}

// 新增 FTP
function op_ftp_add() {
  if (in_array('ftp', from($_POST, 'op', array()))) {
    $username = from($_POST, 'ftp_username');
    $password = from($_POST, 'ftp_password');
    $dir = from($_POST, 'ftp_dir');

    if (!$username) return "<div class='alert alert-danger'>请填写账号</div>";
    if (!$password) return "<div class='alert alert-danger'>请填写密码</div>";
    if (!$dir) return "<div class='alert alert-danger'>请填写目录</div>";

    $list = ftp_list();
    $salt = rand();
    $password = strtoupper(hash("sha512", $password . $salt));
    $dir1 = substr($_SERVER['DOCUMENT_ROOT'], 0, -19). $dir;
    $list[] = array(
      'username' => $username,
      'password' => $password,
      'salt' => $salt,
      'dir' => $dir1,
    );
    ftp_save($list);

    return "<div class='alert alert-success'>FTP 增加成功，重启 FTP 后生效，点击 <a href='javascript:ajax_restart(\"FTP\");' >重启 FTP</a></div>";
  }
}

// 删除 FTP
function op_ftp_del() {
  if (from($_GET, 'act') == 'ftp_del') {
    $id = from($_GET, 'id');
    $force = from($_GET, 'force');
    $list = ftp_list();
    if ($list[$id]['username'] == 'root') return "<div class='alert alert-danger'>系统账号不能删除</div>";
    if ($force) {
      deldir($list[$id]['dir']);
    }
    unset($list[$id]);
    ftp_save($list);
    return "<div class='alert alert-success'>FTP 删除成功，重启 FTP 后生效，点击 <a href='javascript:ajax_restart(\"FTP\");' >重启 FTP</a></div>";
  }
}

// FTP 列表
function ftp_list(){
  $file = FTP_FILE;
  $data = simplexml_load_file($file);
  $data = $data->Users;
  $users = array();
  $data = from($data, 'User', array());
  foreach ($data as $key => $value) {
    $value = json_decode(json_encode($value));
    $username = from(from($value, '@attributes'), 'Name');
    $password = from(from($value, 'Option'), 0);
    $salt = from(from($value, 'Option'), 1);
    $dir = from(from(from(from($value, 'Permissions'), 'Permission'), '@attributes'), 'Dir');
    $users[] = array(
      'username' => $username,
      'password' => $password,
      'salt' => $salt,
      'dir' => $dir,
    );
  }
  return $users;
}

// 保存 FTP
function ftp_save($items){
  $file = FTP_FILE;
  $content = '';
  $content .= '<FileZillaServer>\n    <Settings>\n        <Item name="Admin port" type="numeric">14147</Item>\n    </Settings>\n    <Groups />\n    <Users>\n';
  foreach ($items as $key => $value) {
    $content .= '        <User Name="'.$value['username'].'">\n            <Option Name="Pass">'.$value['password'].'</Option>\n            <Option Name="Salt">'.$value['salt'].'</Option>\n            <Option Name="Group"></Option>\n            <Option Name="Bypass server userlimit">0</Option>\n            <Option Name="User Limit">0</Option>\n            <Option Name="IP Limit">0</Option>\n            <Option Name="Enabled">1</Option>\n            <Option Name="Comments"></Option>\n            <Option Name="ForceSsl">0</Option>\n            <IpFilter>\n                <Disallowed />\n                <Allowed />\n            </IpFilter>\n            <Permissions>\n                <Permission Dir="'.$value['dir'].'">\n                    <Option Name="FileRead">1</Option>\n                    <Option Name="FileWrite">1</Option>\n                    <Option Name="FileDelete">1</Option>\n                    <Option Name="FileAppend">1</Option>\n                    <Option Name="DirCreate">1</Option>\n                    <Option Name="DirDelete">1</Option>\n                    <Option Name="DirList">1</Option>\n                    <Option Name="DirSubdirs">1</Option>\n                    <Option Name="IsHome">1</Option>\n                    <Option Name="AutoCreate">0</Option>\n                </Permission>\n            </Permissions>\n            <SpeedLimits DlType="0" DlLimit="10" ServerDlLimitBypass="0" UlType="0" UlLimit="10" ServerUlLimitBypass="0">\n                <Download />\n                <Upload />\n            </SpeedLimits>\n        </User>\n';
  }
  $content .= '    </Users>\n</FileZillaServer>';
  $content = str_replace('\n', "\n", $content);
  file_put_contents($file, $content);
}

// 新增 MySQL
function op_mysql_add() {
  if (in_array('mysql', from($_POST, 'op', array()))) {
    $username = from($_POST, 'mysql_username');
    $password = from($_POST, 'mysql_password');
    if (!$username) return "<div class='alert alert-danger'>请填写账号</div>";
    if (!$password) return "<div class='alert alert-danger'>请填写密码</div>";
    $con = mysqli_connect(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
    if (!$con){die('Could not connect: ' . mysqli_connect_error());}
    $sql = "CREATE USER '{$username}'@'localhost' IDENTIFIED BY '{$password}';";
    $result = mysqli_query($con, $sql);
    $sql = "CREATE DATABASE IF NOT EXISTS `{$username}`;";
    $result = mysqli_query($con, $sql);
    $sql = "GRANT ALL PRIVILEGES ON `{$username}`.* TO '{$username}'@'localhost';";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    return "<div class='alert alert-success'>MySQL 增加成功</div>";
  }
}

// 删除 MySQL
function op_mysql_del() {
  if (from($_GET, 'act') == 'mysql_del') {
    $user = from($_GET, 'user');
    $host = from($_GET, 'host');
    $force = from($_GET, 'force');
    if ($user == "root" && $host == "localhost") return "<div class='alert alert-danger'>系统账号不能删除</div>";
    $con = mysqli_connect(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
    if (!$con){die('Could not connect: ' . mysqli_connect_error());}
    $sql = "REVOKE ALL PRIVILEGES ON `{$user}`.* FROM '{$user}'@'{$host}';";
    $result = mysqli_query($con, $sql);
    $sql = "DROP USER '{$user}'@'{$host}';";
    $result = mysqli_query($con, $sql);
    if ($force) {
      $sql .= "DROP DATABASE `{$user}`;";
      $result = mysqli_query($con, $sql);
    }
    mysqli_close($con);
    return "<div class='alert alert-success'>MySQL 删除成功</div>";
  }
}

// MYSQL 列表
function mysql_list() {
  $con = mysqli_connect(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
  if (!$con){die('Could not connect: ' . mysqli_connect_error());}
  $result = mysqli_query($con, 'SELECT User, Host, Password FROM mysql.user');
  $data = array();
  while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $data[] = $row;
  }
  mysqli_close($con);
  return $data;
}

// 统计数据库容量
function mysql_count($dbname) {
  return 0;
  // $con = mysqli_connect(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD);
  // if (!$con){die('Could not connect: ' . mysqli_connect_error());}
  // $result = mysqli_query($con, "SELECT sum(DATA_LENGTH)+sum(INDEX_LENGTH) as count FROM information_schema.TABLES where TABLE_SCHEMA='{$dbname}';");
  // $data = array();
  // $row = $result->fetch_array(MYSQLI_ASSOC);
  // mysqli_close($con);
  // return round($row['count'] / 1024 / 1024, 2) . " MB";
}

// 重启服务 
function ajax() {
  $act = from($_POST, "act");
  $obj = from($_POST, "obj");
  if ($act == "ajax_restart") {
    if ($obj == "虚拟主机") {
      $file = ".apache.lock";
      if (file_exists($file)) {
          unlink($file);
        exit(json_encode("ok"));
      } else {
        file_put_contents($file, "1");
        exec('net stop ".apache" & net start ".apache"');
      }
    }
    if ($obj == "MySQL") {
      exec('net stop ".mysql" & net start ".mysql"');
      exit(json_encode("ok"));
    }
    if ($obj == "FTP") {
      exec('net stop "FileZilla Server" & net start "FileZilla Server"');
      exit(json_encode("ok"));
    }
  }
}


function from($array, $key, $default = FALSE)
{
  $return = $default;
  if (is_object($array)) $return = (isset($array->$key) === TRUE && empty($array->$key) === FALSE) ? $array->$key : $default;
  if (is_array($array)) $return = (isset($array[$key]) === TRUE && empty($array[$key]) === FALSE) ? $array[$key] : $default;
  return $return;
}

// 二维数组排序
function multi_array_sort($arr, $key, $short = SORT_ASC) {
    // $short = SORT_ASC, SORT_DESC
    foreach ($arr as $k => $v) {
        $name[$k] = $v[$key];
    }
    array_multisort($name, SORT_REGULAR, $short, $arr);
    return $arr;
}
?>
<script type="text/javascript">
  function ajax_restart(obj) {
    swal({ 
      title: "提示", 
      text: "确认重启 " + obj + " 吗？重启大约需要花费 60 秒。", 
      type: "info", 
      confirmButtonText: "重启", 
      cancelButtonText: "取消",
      showCancelButton: true, 
      closeOnConfirm: false, 
      showLoaderOnConfirm: true, 
    },
      function(){ 
        $.ajax({
          url: "<?php echo $_SERVER['PHP_SELF'] ?>",
          type: "post",
          data: {act: "ajax_restart", obj: obj, },
          dataType: "json",
          cache: false,
          success: function (data){
            if (data == "ok") {
              swal( obj + " 重启完成", "", "success");
            }
          },
        error : function(xhr, textStatus, errorThrown ) {
            $.ajax(this);
        }
      })
     }
  )
  } 
</script>