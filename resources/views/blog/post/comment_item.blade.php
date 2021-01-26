<div class="post clearfix">
    <div class="user-block">
        <div class="div-img-circle" style="background-image: url({{ Storage::url($comment->user->avatar) }})"></div>
        <div class="description">
            <span>
                <a href="{{ route('profile.show',$comment->user_id ) }}">{{ $comment->user->name }}</a>
            </span>

            <div class="mt-1">
                <strong>
                    {{ $comment->created_at->format('H:i') }}
                </strong>
                {{ $comment->created_at->format('d.m.Y') }}
            </div>
        </div>
    </div>
    <!-- /.user-block -->
    <p>
        {{ $comment->comment }}
    </p>
</div>