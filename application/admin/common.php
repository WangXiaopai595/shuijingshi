<?php
/**
 * @param $code 输入的验证码
 * @param string $id 验证码id   多验证码时使用
 * @return bool 返回是否通过验证 布尔值
 */
function check_verify($code, $id = ''){
	$captcha = new \think\captcha\Captcha();
	return $captcha->check($code, $id);
}

/**
 * 重组二维数组
 * @param $array->要组合的二维数组
 * @param $m->str键，组合条件（根据那个键值来组合） 如把相同的id值合并
 * @param $field->相同的字段(不同的装进一个数组)  相同的放在外面
 * @param $fruit->不同集合组成数组的集合键值
 * @return array
 */
function arrayGroup($array,$m,$field,$fruit='group'){
	//重新生成键值0开始递增
	$array = array_values($array);
	if(!$array){
		return $array;
	}
	$res = [];//定义返回的数据，临时数组
	$arr = [];//定义本次要取的数据，临时数组
	$map = $array[0][$m];//定义本次判断的条件
	foreach($array as $k=>$v){
		//如果满足条件，则取出数据
		if($v[$m] == $map){
			//把相同的字段提取出来
			foreach($field as $key=>$value){
				$arr[$value] = $v[$value];
				unset($array[$k][$value]);
			}
			//判断是否存在新的子数组是否存在，根据情况写入数据
			if(isset($arr[$fruit])){
				array_push($arr[$fruit],$array[$k]);
			}else{
				$arr[$fruit] = [];
				array_push($arr[$fruit],$array[$k]);
			}
			unset($array[$k]);
		}
	}
	//把本次循环取出的值写入返回数据
	array_push($res,$arr);
	//递归重复此方法操作取值
	$result = arrayGroup($array,$m,$field,$fruit);
	//判断返回结果是否有值,若有值，写入返回结果
	if($result){
		foreach($result as $k=>$v){
			array_push($res,$v);
		}
	}
	return $res;
}
