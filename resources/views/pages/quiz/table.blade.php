<table id="example2" class="table table-bordered table-hover table-striped" style="width: 100%">
  <thead>
    <tr>
      <th style="width: 7%">No</th>
      <th style="width: 20%">question</th>
      <th>answer</th>
      <th style="width: 19%">Actions</th>
    </tr>
  </thead>
  <tbody>
    
        @foreach($quiz as $index => $quiz)
        <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $quiz->question }}</td>
        <td>{{ $quiz->answer }}</td>

        <!-- button -->
        <td style="text-align: center">
            <form action="/quiz/delete/{{ $quiz->_id }}" method="POST" style="display: inline-block">
            <a href="/quiz/edit/{{ $quiz->_id }}" class="btn btn-warning">
                <i class="fas fa-pencil-alt"></i>
                Edit
            </a>
            @csrf
            @method('DELETE')
            <button href="" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
                Delete
            </button>
            </form>
        </td>
        </tr>
        @endforeach
  </tbody>
</table>