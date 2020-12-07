<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/admin_util.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <p class="greeting">ログイン名[渡部智哉]さん、ようこそ</p>
                <p class="logout"><a href="../index.php">メイン</a> <a href="logout.php">ログアウトする</a></p>
            </div>
            <div class="first-header">
                <nav class="navbar navbar-expand-sm navbar-dark mb-3 first">
                    <ul class="navbar-nav">
                        <li class="nav-item active"><a class="nav-link" href="/">TOP</a></li>
                        <li class="nav-item active"><a class="nav-link" href="/product_list">商品管理</a></li>
                        <li class="nav-item active"><a class="nav-link" href="purchase_list.php">注文情報</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        @yield('content')
        <footer>
            <p>2020 ebacorp.inc</p>
        </footer>
    </div>
</body>
</html>
