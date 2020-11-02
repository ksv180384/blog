<form id="formAddFollow" action="{{ route('follow.add') }}" method="post">
    @csrf
    <input type="hidden" name="to_user_id" value="{{ $user_item->id }}">
    <button class="btn btn-primary btn-block" id="followBtn"><strong>Отслеживать</strong></button>
</form>