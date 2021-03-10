@extends('layouts.app')
@section('title', 'id: ' .  $message->id .  'の投稿詳細')
@section('content')
            <div class="row mt-2">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>id</th>
                        <td>{{ $message->id }}</td>
                    </tr>
                    <tr>
                        <th>名前</th>
                        <td>{{ $message->name }}</td>
                    </tr>
                    <tr>
                        <th>タイトル</th>
                        <td>{{ $message->title }}</td>
                    </tr>
                    <tr>
                        <th>内容</th>
                        <td>{{ $message->body }}</td>
                    </tr>
                    <tr>
                        <th>画像</th>
                        <td><img src="{{ asset('uploads') }}/{{ $message->image }}" alt="表示する画像がありません。"></td>
                    </tr>
                </table>
            </div> 
            
            <div class="row">
                <!--<a href="edit.php?id=<{{ $message->id }}" class="col-sm-6 btn btn-primary">編集</a>-->
                <a href="/messages/{{ $message->id }}/edit" class="col-sm-6 btn btn-primary">編集</a>
                <!--<form class="col-sm-6" action="delete.php" method="POST">-->
                <form class="col-sm-6" action="/messages/{{ $message->id }}" method="POST">
                    <!--CSRFトークンを生成-->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <!--<input type="hidden" name="id" value="{{ $message->id }}">-->
                    <button type="submit" class="btn btn-danger col-sm-12" onclick="return confirm('投稿を削除します。よろしいですか？')">削除</button>
                </form>
            </div>
            
            <div class="row mt-5">
                <!--<a href="index.php" class="btn btn-primary">投稿一覧</a>-->
                <a href="/messages" class="btn btn-primary">投稿一覧</a>
            </div>
        </div>
@endsection