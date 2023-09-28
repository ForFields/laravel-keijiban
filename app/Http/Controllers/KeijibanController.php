<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Keijiban;

class KeijibanController extends Controller
{
    //入力した値を受け取る処理
    // public function uketoru(Request $request) {
    //     $name = $request->input('name');
    //     $contents = $request->input('contents');
    //     return view('/keijiban2', compact('name', 'contents'));
    // }

    public function insert(Request $request)
    {
        //バリデーション
        $request->validate(
            [
                'name' => ['required', 'max:50'],
                'contents' => ['required', 'max:1024'],
            ],
            [
                'name.required' => '名前は必須です',
                'contents.required' => '内容は必須です',
                'name.max' => '50文字以内で入力してください',
                'contents.max' => '1024文字以内で入力してください'
            ]
        );

        $keijiban = new Keijiban();
        $keijiban->user_name = $request->name;
        $keijiban->contents = $request->contents;
        $keijiban->save();

        return redirect('/keijiban');
    }

    public function display()
    {
        $keijiban = new Keijiban();
        $data = $keijiban->getData();

        return view('/keijiban', ['data' => $data]);
    }

    public function update(Request $request)
    {
        //バリデーション
        $request->validate(
            [
                'name' => ['required', 'max:3'],
                'contents' => ['required', 'max:1024'],
            ],
            [
                'name.required' => '名前は必須です',
                'contents.required' => '内容は必須です',
                'name.max' => '50文字以内で入力してください',
                'contents.max' => '1024文字以内で入力してください'
            ]
        );

        $keijiban = Keijiban::find($request->id);
        $keijiban->user_name = $request->name;
        $keijiban->contents = $request->contents;
        $keijiban->save();

        return redirect('/keijiban');
    }

    public function delete(int $id)
    {
        Keijiban::destroy($id);

        return redirect('/keijiban');
    }
}
