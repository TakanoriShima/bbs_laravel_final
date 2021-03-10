<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Messageモデルを使って全データ取得
        $messages = Message::all();

        // セッションからフラッシュメッセージ取得しセッション情報破棄
        $flash_message = session('flash_message');
        session()->forget('flash_message');
        
        // データを引き連れてviewへ移動
        return view('messages.index', compact('messages', 'flash_message'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // セッションからエラーメッセージを取得しセッション情報破棄
        $errors = session('errors');
        session()->forget('errors');
        
        // 空のメッセージインスタンスを作成
        $message = new Message();
    
        // データを引き連れてviewへ移動
        return view('messages.create', compact('message', 'errors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 入力情報の取得
        $name = $request->input('name');
        // $name = $request->name;
        $title = $request->input('title');
        $body = $request->input('body');
        $file =  $request->image;
        
        // https://qiita.com/ryo-program/items/35bbe8fc3c5da1993366
        if ($file) { // ファイルが選択されていれば
            // 現在時刻ともともとのファイル名を組み合わせてランダムなファイル名作成
            $image = time() . $file->getClientOriginalName();
            // アップロードするフォルダ名取得
            $target_path = public_path('uploads/');
            // アップロード処理
            $file->move($target_path, $image);
        } else { // ファイルが選択されていなければ
            $image = "";
        }
        
        // 入力情報をもとに新しいインスタンス作成
        $message = new Message();
        $message->name = $name === null ? '' : $name;
        $message->title = $title === null ? '' : $title;
        $message->body = $body === null ? '' : $body;
        $message->image = $image;
        
        // $message->name = $name;
        // $message->title = $title;
        // $message->body = $body;
        // $message->image = $image;

        $errors = $message->validate($message);
        
        // 入力エラーが1つもなければ
        if(count($errors) === 0){
            
            // データベースに保存
            $message->save();
            
            // フラッシュメッセージをセッションに保存
            session(['flash_message' => '投稿が成功しました。']);
            // $request->session()->flash('flash_message', '投稿が成功しました。');
            
            // index action へリダイレクト
            return redirect('/');
            // return redirect('/')->with('flash_message', '投稿が成功しました。');

        }else{
            // セッションにエラー配列をセット
            session(['errors' => $errors]);

            // create action へリダイレクト
            return redirect('/messages/create');

        }
        
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        // セッションからフラッシュメッセージ取得しセッション情報破棄
        $flash_message = session('flash_message');
        session()->forget('flash_message');
        
        // データを引き連れてviewへ移動
        return view('messages.show', compact('message', 'flash_message'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        // セッションからエラーメッセージを取得しセッション情報破棄
        $errors = session('errors');
        session()->forget('errors');
        
        // データを引き連れてviewへ移動
        return view('messages.edit', compact('message', 'errors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        // 入力情報の取得
        $name = $request->input('name');
        $title = $request->input('title');
        $body = $request->input('body');
        $file =  $request->image;
        
        // https://qiita.com/ryo-program/items/35bbe8fc3c5da1993366
        if ($file) { // ファイルが選択されていれば
            // 現在時刻ともともとのファイル名を組み合わせてランダムなファイル名作成
            $image = time() . $file->getClientOriginalName();
            // アップロードするフォルダ名取得
            $target_path = public_path('uploads/');
            // アップロード処理
            $file->move($target_path, $image);
        } else { // ファイルが選択されていなければ元の画像のファイル名のまま
            $image = $message->image;
        }
        
        // インスタンス情報の更新
        // $message->name = $name;
        // $message->title = $title;
        // $message->body = $body;
        // $message->image = $image;
        
        $message->name = $name === null ? '' : $name;
        $message->title = $title === null ? '' : $title;
        $message->body = $body === null ? '' : $body;
        $message->image = $image;

        $errors = $message->validate($message);
        
        // 入力エラーが1つもなければ
        if(count($errors) === 0){
            
            // データベースに保存
            $message->save();
            
            // フラッシュメッセージ作成
            $flash_message = 'id: ' . $message->id . 'の投稿が更新されました。';
            
            // フラッシュメッセージをセッションに保存
            session(['flash_message' => $flash_message]);
            
            // show action へリダイレクト
            return redirect('/messages/' . $message->id);

        }else{
            // セッションにエラー配列をセット
            session(['errors' => $errors]);

            // edit action へリダイレクト
            return redirect('/messages/' . $message->id . '/edit');

        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        // フラッシュメッセージ作成
        $flash_message = 'id: ' . $message->id . 'の投稿が削除されました。';
        
        // データベースからデータを削除
        $message->delete();
        
        // フラッシュメッセージをセッションに保存
        session(['flash_message' => $flash_message]);
        
        // index action へリダイレクト
        return redirect('/');
        
        // return redirect('/')->with('flash_message', $flash_message);
    }
}
