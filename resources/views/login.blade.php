<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/admin_login.css')}}">
    <title>管理画面</title>
</head>
<body>
    <main>
        <div class="card">
            <div class="card-body">
                <h1>洋菓子店カサミンゴー 管理者ログイン</h1>
                @if(count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <p class="error">{{ $error }}</p>
                    @endforeach
                @endif
                @if(isset($otherError))
                    <p class="error">{{ $otherError }}</p>
                @endif
                <form action="/login_val" method="post">
                    @csrf
                    <table>
                        <tr>
                            <th>ログインID</th>
                            <td><input type="text" name="login_id" value="<?=isset($loginId) ? $loginId : old('login_id')?>"></td>
                        </tr>
                        <tr>
                            <th>パスワード</th>
                            <td><input type="password" name="login_pass"></td>
                        </tr>
                    </table>
                    <p><input type="submit" name="login" value="ログイン"></p>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
