<!-- Beginning of pages/userActivity/filter.blade.php -->

{{--
    * Table Structure
    * desc User_Activity;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | id         | bigint(20)          | NO   |     | NULL                |                |
    | classID    | varchar(85)         | NO   |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Purpose    | varchar(85)         | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    7 rows in set (0.00 sec)
--}}

@if(Entrust::hasRole(['support']))
    @include('fields.textEntry', ['fieldName' => 'id'         ])
    @include('fields.textEntry', ['fieldName' => 'classID'    ])
@endif

{{-- _if(Entrust::hasRole(['receiptSuper','receiptManager','support'])) --}}
@if(Entrust::hasRole(['teamLead','super','manager','support']))
	@include('fields.textEntry', ['fieldName' => 'User_Name'])
@else
	<div class="form-group">
		<label for="User_Name" class="col-md-4 control-label">@lang('labels.User_Name')</label>
		<div class="col-md-8">
			<div class="form-control mark">
				{{ $userActivity['User_Name'] }}
			</div>
		</div>
	</div>
@endif

@include('fields.dateEntry', ['fieldName' => 'created_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.dateEntry', ['fieldName' => 'updated_at', 'fieldFormat' => 'Y-m-d H:i', 'validateOnBlur' => 'false', 'onChangeSubmit' => 'true' ])
@include('fields.textEntry', ['fieldName' => 'Purpose'    ])

@include('fields.button')

<!-- End of pages/userActivity/filter.blade.php -->
