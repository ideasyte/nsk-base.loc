<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}

?>
            <div class="row">
                <div class="col-md-12">
                    <div class="row margin">
                        <div class="col-md-3">
                            <a href="#" class="btn btn-block btn-success btn-sm" onclick="open_modal(0, 'clients', 'edit');">
								<i class="fa fa-fw fa-plus"></i>Покупатель
							</a>
						</div>
                        <div class="col-md-3">
                            <input type="radio" name="pay_type" value="1">
                            <label>Наличка</label>
                        </div>
                        <div class="col-md-3">
                            <input type="radio" name="pay_type" value="2">
                            <label>Ипотека</label>
                        </div>
                        <div class="col-md-3">
                            <input type="radio" name="pay_type" value="0">
                            <label>Иное</label>
                        </div>
                    </div>

                    <div class="box box-success">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row margin">
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_rooms_1"> 1-шка
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_rooms_2"> 2-шка
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_rooms_3"> 3-шка
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-1">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_rooms_4"> 4-х
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-1">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_rooms_etc"> Более
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_non_residential"> Нежилое помещение
                                        </label>
                                    </div>
                                </div>
								<div class="col-sm-2">
									<!--Progressbar of loading process-->
                                    <i class="fa fa-spin fa-spinner progressbar" style="display:none;"></i>&nbsp;
                                </div>								
                            </div>
                            <div class="row margin">
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_sharing"> Доля (подселение)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_room_only"> Комната
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_studio"> Студия
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-1">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_house"> Дом
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-1">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_land_only"> Земля
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_house_with_land"> Земля с домом
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cl_garage"> Гараж
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-success">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row margin">
                                <div class="col-sm-12">
                                    <label>Цена</label><input id="price" type="text" name="price" value=""/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-body">

                            <div class="row margin">

                                <div class="col-sm-12">
                                    <label>Площадь</label><input id="area" type="text" name="length" value=""/>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.row -->