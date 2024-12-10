<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{ asset('imgs/favicon.ico') }}" sizes="any" type="image/x-icon">

    @vite([
        // CSS
        'resources/css/forums/index.css',
        'resources/css/forums/main/index.css',
        'resources/css/forums/main/like.css',
        'resources/css/forums/main/empty.css',

        'resources/css/forums/dialogs/confirmDel.css',

        // JS
        'resources/js/forums/updateLikes.js',
        'resources/js/forums/orderBy.js',
        'resources/js/forums/dialogs/confirmDel.js',
        'resources/js/forums/gotoComments.js',
    ])

    <script>
        const likeData = @json($likesData);
        const roles = @json($roles);
        let URLComment = "{{ route('forum.viewComments', ['id' => '0']) }}";
    </script>

    <title>WellWoman - Foros</title>
</head>

<body loading="lazy">
    @include('main.header')

    <main>
        @if (session('postDeleted'))
            <x-notification-success message="Se ha eliminado el post" subtext="" />
        @elseif (session('postCreate'))
            <x-notification-success message="Se ha creado el post" subtext="" />
        @endif

        <section id="notification-container" class="hidden">
            <x-notification-success message="Se ha guardado tu like." subtext="" />
        </section>

        @auth
            <section class="container">
                <div class="input">
                    <form action="{{ route('forum.addForum') }}" method="POST">
                        @csrf

                        <h1>Crear Foro</h1>
                        <input class="title" type="text" name="name" placeholder="Titulo" required>
                        <textarea name="content" placeholder="Escribe aquí tu post" required></textarea>

                        <div class="buttonspost">
                            <input type="submit" class="send-post" value="Publicar">
                        </div>
                    </form>
                </div>
            </section>
        @endauth

        <section class="container post-container">
            @if ($foros->isEmpty())
                <section class="empty-forum">
                    <div class="empty-forum-message">
                        <p>Vaya, esta algo vacío por aquí...</p>
						
						@auth
							<p>¿Rompamos el hielo vale?</p>
							<a href="#create-forum" class="create-forum-link">¡Intenta crear un foro!</a>
						@endauth
                    </div>
                    <img src="https://i.ibb.co/X4HPYZc/nkosearch.webp" alt="Imagen Foro Vacío"
                        class="empty-forum-image">
                </section>
            @else
                @foreach ($foros as $foro)
                    <div class="post" data-post-id="{{ $foro->id }}">

                        <div class="post-header">
                            <img src="{{ $foro->creador->profile_url }}" alt="Avatar" class="avatar">
                            <div class="post-info">
                                <p class="name">{{ $foro->creador->name }}</p>
                                <p class="date">{{ $foro->time_creation }}</p>
                            </div>
                        </div>

                        <div class="post-body">
                            <h2>{{ $foro->name }}</h2>
                            <p class="post-text">{{ $foro->content }}</p>
                        </div>

                        <div class="post-footer">
                            <div class="post-actions">

                                <div class="btns-post">
                                    <form class="form-post"
                                        action="{{ route('forum.updateLikes', ['id' => $foro->id]) }}" method="post">
                                        @csrf

                                        <label class="like-container">
                                            <input type="checkbox" name="like-input" class="like-checkbox">

                                            <div class="checkmark">
                                                <svg viewBox="0 0 50 50" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path
                                                        d="M 24.10 6.29 Q 28.34 7.56 28.00 12.00 Q 27.56 15.10 27.13 18.19 A 0.45 0.45 4.5 0 0 27.57 18.70 Q 33.16 18.79 38.75 18.75 Q 42.13 18.97 43.23 21.45 Q 43.91 22.98 43.27 26.05 Q 40.33 40.08 40.19 40.44 Q 38.85 43.75 35.50 43.75 Q 21.75 43.75 7.29 43.75 A 1.03 1.02 0.0 0 1 6.26 42.73 L 6.42 19.43 A 0.54 0.51 -89.4 0 1 6.93 18.90 L 14.74 18.79 A 2.52 2.31 11.6 0 0 16.91 17.49 L 22.04 7.17 A 1.74 1.73 21.6 0 1 24.10 6.29 Z M 21.92 14.42 Q 20.76 16.58 19.74 18.79 Q 18.74 20.93 18.72 23.43 Q 18.65 31.75 18.92 40.06 A 0.52 0.52 88.9 0 0 19.44 40.56 L 35.51 40.50 A 1.87 1.83 5.9 0 0 37.33 39.05 L 40.51 23.94 Q 40.92 22.03 38.96 21.97 L 23.95 21.57 A 0.49 0.47 2.8 0 1 23.47 21.06 Q 23.76 17.64 25.00 12.00 Q 25.58 9.36 24.28 10.12 Q 23.80 10.40 23.50 11.09 Q 22.79 12.80 21.92 14.42 Z M 15.57 22.41 A 0.62 0.62 0 0 0 14.95 21.79 L 10.01 21.79 A 0.62 0.62 0 0 0 9.39 22.41 L 9.39 40.07 A 0.62 0.62 0 0 0 10.01 40.69 L 14.95 40.69 A 0.62 0.62 0 0 0 15.57 40.07 L 15.57 22.41 Z"
                                                        fill-opacity="1.000"></path>
                                                    <circle r="1.51" cy="37.50" cx="12.49" fill-opacity="1.000">
                                                    </circle>
                                                </svg>
                                            </div>

                                            <p class="like">Yei!</p>
                                        </label>

                                        <span
                                            class="like-count {{ $foro->likes == 0 ? 'hidden' : '' }}">{{ $foro->likes }}</span>
                                    </form>
                                </div>

                                <div class="btns-post">
                                    <div class="commentsBtn btn-img-post">
                                        <span class="forums-icon material-symbols-outlined">forum</span>
                                    </div>

                                    <span class="comment-count {{ $foro->comentarios->count() == 0 ? 'hidden' : '' }}">
                                        {{ $foro->comentarios->count() }}
                                    </span>
                                </div>

                                <div class="btns-post">
                                    <div class="report-icon btn-img-post">
                                        <span class="material-symbols-outlined">report</span>
                                    </div>
                                </div>

                                <div class="btns-post">
                                    <button type="button" class="delete-post">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </section>

        <dialog id="confirmDel">
            <div class="dialog-content">
                <h2>Eliminacion del post</h2>
                <p>¿Estás seguro de que deseas eliminar este post?</p>
                <div class="dialog-buttons">
                    <button type="button" id="cancelBtn">Cancelar</button>

                    <form class="formDel" action="{{ route('forum.deletedPost', ['id' => '0']) }}" method="post">
                        @csrf

                        <button type="submit" id="confirmBtn" class="confirm">Eliminar</button>
                    </form>
                </div>
            </div>
        </dialog>
    </main>

    @include('main.footer')
</body>

</html>
