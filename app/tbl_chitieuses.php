<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\Nodetrait;

class tbl_chitieuses extends Model
{
	protected $guarded = [];

	use NodeTrait;

	public function tbl_chitietsolieutheobieu()
	{
		return $this->hasMany('App\tbl_chitietsolieutheobieu', 'chitieu');
	}
}
