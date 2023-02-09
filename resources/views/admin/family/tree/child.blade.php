<ul>
   @foreach ($families as $item)
   <li><i class="fas fa-circle"></i> 
      <a href="#">{{$item->name}}</a>
       @if ($item->child->count()>0)
           @include('admin.family.tree.child', ['families' => $item->child])
       @endif
   </li>
   @endforeach
</ul>