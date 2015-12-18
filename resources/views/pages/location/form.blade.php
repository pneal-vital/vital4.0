<!-- Beginning of pages/location/form.blade.php -->

{{--
    * Location;
    +---------------+-------------+------+-----+---------+-------+
    | Field         | Type        | Null | Key | Default | Extra |
    +---------------+-------------+------+-----+---------+-------+
    | objectID      | bigint(20)  | NO   | PRI | NULL    |       |
    | Location_Name | varchar(85) | YES  | MUL | NULL    |       |
    | Capacity      | varchar(85) | YES  |     | NULL    |       | in ('', 1, 6, 999, 9999), set to 1
    | x             | varchar(85) | YES  |     | NULL    |       |
    | y             | varchar(85) | YES  |     | NULL    |       |
    | z             | varchar(85) | YES  |     | NULL    |       |
    | LocType       | varchar(85) | YES  | MUL | NULL    |       | may be '', 'ACTIVITY', 'RESERVE', 'WORK', or 'PICK' + pick Sequence number
    | Comingle      | varchar(85) | YES  |     | NULL    |       | in ('N', 'P')
    +---------------+-------------+------+-----+---------+-------+
--}}

@include('fields.textEntry', ['fieldName' => 'Location_Name'])
@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'Capacity'])
    @include('fields.textEntry', ['fieldName' => 'x'])
    @include('fields.textEntry', ['fieldName' => 'y'])
    @include('fields.textEntry', ['fieldName' => 'z'])
@endif
@include('fields.textEntry', ['fieldName' => 'LocType'])
@include('fields.textEntry', ['fieldName' => 'Comingle'])

@include('fields.button')

<!-- End of pages/location/form.blade.php -->
