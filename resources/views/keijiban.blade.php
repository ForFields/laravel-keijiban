@extends('layouts.header')

@section('keijiban')
    <div class="container w-50">
        <h2 class="text-center">表示欄</h2>
        <div>
            @foreach ($data as $values)
                <div class="content-container">
                    <div class="flex-container">
                        <div class="k-id fw-bold">{{ $values->id }}</div>
                        <div class="k-user fw-bold">{{ $values->user_name }}</div>
                        <div class="k-time fw-bold">{{ $values->created_at }}</div>
                        <div class="k-menu">
                            <div class="dropdown">
                                <button class="btn btn-sm border-0" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu" style="width: 250px" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <div class="card">
                                            <div class="card-body">
                                                <form action="/keijiban/{{ $values->id }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div>
                                                        <lavel for="name" class="form-lavel">名前</lavel>
                                                        @if ($errors->has('name'))
                                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                                        @endif
                                                        <input type="text" class="form-control"
                                                            value="{{ $values->user_name }}" name="name">
                                                    </div>
                                                    <div>
                                                        <lavel for="contents" class="form-lavel">内容</label>
                                                            @if ($errors->has('contents'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('contents') }}</span>
                                                            @endif
                                                            <textarea class="form-control" name="contents" rows="5" cols="30">{{ $values->contents }}</textarea>
                                                    </div>
                                                    <input type="submit" class="d-inline btn btn-outline-secondary"
                                                        name="send" value="編集">
                                                </form>
                                                <form action="/keijiban/{{ $values->id }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="submit" class="d-inline btn btn-outline-secondary"
                                                        value="削除">
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div>{{ $values->contents }}</div>
                </div>
            @endforeach
        </div>
        {{-- 新規登録 ここから --}}
        <form method="POST" action="/keijiban">
            @csrf
            <div class="card mt-5 w-75 mx-auto">
                <div class="card-body">
                    <div class="pt-0">
                        <lavel for="name" class="form-lavel">名前</lavel>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="pt-2">
                        <lavel for="contents" class="form-lavel">内容</label>
                            @if ($errors->has('contents'))
                                <span class="text-danger">{{ $errors->first('contents') }}</span>
                            @endif
                            <textarea class="form-control" name="contents" rows="5" cols="30"></textarea>
                    </div>
                    <div class="card-footer bg-transparent text-end">
                        <input type="submit" class="btn btn-outline-secondary" name="send" value="送信">
                    </div>
                </div>
            </div>
        </form>
        {{-- 新規登録 ここまで --}}
    </div>
@endsection
