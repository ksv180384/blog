<div class="post clearfix">
    <div class="user-block">
        <div class="div-img-circle" style="background-image: url({{ Storage::url($comment->user->avatar) }})"></div>
        <span class="username">
            <a href="{{ route('profile.show',$comment->user_id ) }}">{{ $comment->name }}</a>
        </span>
        <span class="description">{{ $comment->created_at }}</span>
    </div>
    <!-- /.user-block -->
    <p>
        {{ $comment->comment }}
    </p>
</div>