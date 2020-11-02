<form id="formDestriyFollow"
      action="{{ route('follow.destroy', $follow_check->id) }}"
      method="post">
    @csrf
    <button class="btn btn-block btn-default btn-xs" id="followDestroyBtn">
        <strong>Не отслеживать</strong>
    </button>
</form>