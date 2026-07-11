@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
@endsection

@section('main')
<div class="admin-dashboard">
    <header class="admin-dashboard__header">
        <h1 class="admin-dashboard__logo">ReseAdmin</h1>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="admin-dashboard__logout">Logout</button>
        </form>
    </header>

    <main class="admin-dashboard__main">
        <div class="admin-dashboard__card">
            <nav class="admin-dashboard__nav">
                <a href="{{ route('admin.owners.create') }}" class="admin-dashboard__link">
                    店舗代表者登録
                </a>
                <a href="{{ route('admin.owners.index') }}" class="admin-dashboard__link">
                    店舗代表者一覧
                <a href="{{ route('admin.reviews.shops') }}" class="admin-dashboard__link">
                    店舗別口コミ一覧
                </a>
            </nav>

            <section class="admin-dashboard__csv">
                <h2 class="admin-dashboard__csv-title">CSVファイルインポート</h2>

                <form method="POST" action="{{ route('admin.upload_csv') }}" enctype="multipart/form-data">
                    @csrf

                    <label class="admin-dashboard__file-area">
                        <span>クリックまたはドラッグアンドドロップ</span>
                        <input type="file" name="csv_file">
                    </label>

                    <p class="admin-dashboard__file-text">選択されていません</p>

                    <button type="submit" class="admin-dashboard__import">
                        インポート
                    </button>
                </form>
            </section>
        </div>
    </main>
</div>
@endsection