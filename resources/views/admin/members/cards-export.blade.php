<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
@include('admin.members._card-styles')
</head>
<body>

@foreach($members as $i => $member)
    @include('admin.members._card', ['lastCard' => $i === count($members) - 1])
@endforeach

</body>
</html>
