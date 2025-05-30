<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <header>
        <h1>Dashboard</h1>
        <p>user: {{ auth()->user()->name ?? 'Guest' }}</p>
    </header>
    <main>
        <div class="note-form">
            <form action="{{ route('notes.create') }}" method="POST">
                @csrf
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required @guest disabled @endguest>
                <label for="content">New Note:</label>
                <textarea id="content" name="content" required @guest disabled @endguest></textarea>
                <button type="submit" @guest disabled @endguest>@auth
                    Create Note
                @endauth
                @guest
                    Please log in to create notes.
                @endguest
                </button>
            </form>
        <div class="note-container-group">
        @foreach ($notes as $note)
            <div class="note-container">
                <form action="{{ route('notes.update', $note->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h2>{{ $note->title }}</h2>
                    <textarea name="content"
                    @guest
                        disabled
                    @endguest
                    @auth
                    @if(auth()->user()->id !== $note->uid)
                        disabled
                    @endif
                    @endauth
                    >{{ $note->content }}</textarea>
                    @auth
                    @if(auth()->user()->id === $note->uid)
                    <button type="submit">Update</button>
                    @endif
                    @endauth
                </form>
                @auth
                @if (auth()->user()-> id === $note->uid)
                <form action="{{ route('notes.delete', $note->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
                @endif
                @endauth
            </div>
        @endforeach
        </div>
    </main>
    <footer>
        <a href="{{ route('logout') }}">Logout</a>
    </footer>
</body>
</html>