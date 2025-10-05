<?php
$username = $this->request->session()->read('Auth.User.first_name')." ".$this->request->session()->read('Auth.User.last_name');
?>
<!-- dashboard title Start -->

<?= $this->element('c_breadcrumb'); ?>

<!-- dashboard title End -->

<?php echo $this->Html->script(['highcharts-custom.js','highcharts.js','highcharts-more.js','solid-gauge.js']); ?>

<!-- blue banner Start -->
<div class="blue-banner dashboard-banner row">
    <div class="container">
        <div class="user-detail">
            <div class="thumb">
                <div class="thumb-inner">
					<?php
                        $proImage = $this->Custom->getProfileImage($userDetails['image']);
                        echo $this->Html->image($proImage);
                    ?>
                </div>
            </div>
            <div class="detail-right">
                <h2><?= $username ?></h2>
                <ul>
                    <li>
						<?= $this->Html->link(($notiCount!=0)?'<span>'.$notiCount.'</span>':'',['controller' => 'notifications', 'action' => 'index'],['class' => 'n-icon1','escape' => false]); ?>
                    </li>
                    <li><?= $this->Html->link(($msgCount!=0)?'<span>'.$msgCount.'</span>':'',['controller' => 'messages', 'action' => 'inbox'],['class' => 'n-icon3','escape' => false]); ?></li>
                    <li>
                        <?= $this->Html->link('',['controller' => 'users', 'action' => 'my_profile'],['class' => 'n-icon2']); ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tracker-sec">
            <h4>My Weightloss Tracker</h4>

            <!--<div class="chart-div" >
				<?php //echo $this->Html->image('chart.png'); ?>
            </div>-->
            <div class="chart-div" id="chart-container">

            </div>

            <div class="one_half">
				<?= $this->Html->link('Add Today'."'".'s Weight','javascript:void(0)',array('class' => 'btn white-btn addWeight_popup_open'));?>
            </div>
            <div class="one_half last">
                <div class="thumb">
                    <div class="thumb-inner">
						<?php
                            if(isset($userDetails->trainer->image))
                                $trainerImage = $userDetails->trainer->image;
                            else
                                $trainerImage = "";

                            $proImage = $this->Custom->getProfileImage($trainerImage);
                            echo $this->Html->image($proImage);
                        ?>
                    </div>
                </div>
                <div class="detail-right">
					<?php
					if(isset($userDetails->trainer) && !empty($userDetails->trainer)){
						echo $this->Html->link('<h2>'.$userDetails->trainer->first_name.' '.$userDetails->trainer->last_name.'</h2>',['controller' => 'trainers', 'action' => 'index'],['escape' => false]);
						echo $this->Html->link('Contact Coach','javascript:void(0)',array('class' => 'btn green-btn message_popup_open'));
            		}
					else
					{
						echo $this->Html->link('<h2>Coach Not Assigned</h2>','javascript::void(0)',['escape' => false]); 
						echo $this->Html->link('Contact Coach','javascript::void(0)',['class' => 'btn green-btn']);
					}
					?>
                </div>
            </div>
        </div>	

        <div class="progress-block">
            <div class="progress-panel one-third">
                <div class="prog-img" id="totalWeightLoss">
					<?= (!empty($userDetails->total_weight_loss))?$userDetails->total_weight_loss."lbs":"--"; ?>
                </div>
                <p><strong>total</strong> weight loss</p>
            </div>
            <div class="progress-panel one-third">
                <div class="prog-img" id="monthWeightLoss">
				    <?= (!empty($userDetails->month_weight_loss))?$userDetails->month_weight_loss."lbs":"--"; ?>
                </div>
                <p><strong>Current MOnth</strong> weight loss</p>
            </div>
            <div class="progress-panel one-third">
                <div class="prog-img" id="weekWeightLoss">
				    <?= (!empty($userDetails->week_weight_loss))?$userDetails->week_weight_loss."lbs":"--"; ?>
                </div>
				<p><strong>This Week</strong> weight loss</p>
	        </div>
		</div>
	</div>
</div>
<!-- blue banner end -->

<?php
    echo $this->element('home_community');
    echo $this->Form->create('User', array('class' => 'payment-form', 'id' => 'UserForm','type' => 'file'));
    echo $this->element('home_uploads');
    echo $this->element('home_leaderboard');
    echo $this->element('home_photos');
    echo $this->Form->end();
    echo $this->element('weight_model');
    echo $this->element('crop_model');
    echo $this->element('message_model');
?>

<script type="text/javascript">
    var UserWeightJsonGlobal = <?php echo $UserWeightJson;?>;
	var GoalWeight = <?php echo $this->request->session()->read('Auth.User.goal_weight');?>;
    var chart = null;
	var goalWeightJson = [];
	if (!jQuery.isEmptyObject(UserWeightJsonGlobal)) {
       for(var i=0; i < UserWeightJsonGlobal.y.length; i++) {
			goalWeightJson.push(GoalWeight);
		}
    }
	
    $(document).ready(function(){
        $('#filterOptions li a').click(function(){
            if($(this).hasClass('pics')){
                $('#picsHolder').show();
                $('#videosHolder').hide();
            }
            else{
                $('#picsHolder').hide();
                $('#videosHolder').show();
            }
        });
        if($('#UserWeightForm').length){
            $('#UserWeightForm').submit(function(event){
                event.preventDefault();
                $.ajax({
                    'url'       :   base_url+'users/addweight',
                    'type'      :   'post',
                    'dataType'  :   'json',
                    'async'     : 	false,
                    'data'      :   $(this).serialize(),
                    'success'   :   function(data){ 
                        if(data.status == 0){
                            $('#addweightTooltip span').html(data.message);
                            $('#addweightTooltip').show();
                            $('.tooltips').delay(5000).fadeOut('slow');
                        }
                        else{
                            window.location.href = window.location.href;
                        }
                    }
                });
            });
        }

        if($('#MessageForm').length){
            $('#MessageForm').submit(function(event){
                event.preventDefault();
                $.ajax({
                    'url'       :   base_url+'messages/send',
                    'type'      :   'post',
                    'dataType'  :   'json',
                    'async'     : false,
                    'data'      :   $(this).serialize(),
                    'success'   :   function(data){ 
                        if(data.status == 0){
                            $('#messageTooltip span').html(data.message);
                            $('#messageTooltip').show();
                            $('.tooltips').delay(5000).fadeOut('slow');
                        }
                        else{
                            $('#messageTooltip span').html(data.message);
                            $('#messageTooltip').show();
                            $('.tooltips').delay(5000).fadeOut('slow');
                            $('#message_popup').popup("hide");
                        }
                    }
                });
            });
        }

        $('#addWeight_popup').popup({
          transition: 'all 0.3s',
          scrolllock: true, // optional
        });

        $('#message_popup').popup({
          transition: 'all 0.3s',
          scrolllock: true, // optional
        });

        $("#weight_date").datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: '1950:2016',
        });

        loadWeightGraph(UserWeightJsonGlobal, goalWeightJson);
    });
    
    function loadWeightGraph(UserWeightJson, goalWeightJson){
        var chartOptions = {
			chart: {
				renderTo: 'chart-container',

				backgroundColor: '#15A4E5'
			},
			credits: {
				enabled: false
			},
			title: false,
			subtitle: false,
			xAxis: {
				categories : UserWeightJson.x,
				gridLineWidth: 1,
				labels: {
					overflow: 'justify',
					formatter: function () {
						//return Highcharts.dateFormat('%d %b %Y', this.value);
						return this.value;
					},
					style: {
						color: '#FFFFFF',
						fontWeight: 'bold',
						fontSize: '13px',
						fontFamily: 'Trebuchet MS, Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				gridLineWidth: 1,
				title: {
					text: 'Weight (in lbs)',
					style: {
						color: '#FFFFFF',
						fontWeight: 'bold',
						fontSize: '15px',
						fontFamily: 'Trebuchet MS, Verdana, sans-serif',
						textTransform: "uppercase"
					}
				},
				labels: {
					style: {
						color: '#FFFFFF',
						fontWeight: 'bold',
						fontSize: '15px',
						fontFamily: 'Trebuchet MS, Verdana, sans-serif',
						textTransform: "uppercase"
					}
				},
				plotLines: [{
						value: 0,
						width: 1,
						color: '#000000'
					}]
			},
			tooltip: {                    
				formatter: function() {
					// If you want to see what is available in the formatter, you can
					// examine the `this` variable.
					//     console.log(this);
					return '<b>'+ Highcharts.numberFormat(this.y, 2) +' lbs</b><br/>'+
						 this.x;
				}
			},
			legend: {
				enabled: false
			},
			series: [{
					name: 'Berlin',
					color: '#63E24F',
					lineWidth: 3,
					data: UserWeightJson.y
				}, {
					name: 'New York',
					color: '#FF0000',
					lineWidth: 3,
					data: goalWeightJson
				}]
		};
        //chart = $('#chart-container').highcharts();
        chart = new Highcharts.Chart(chartOptions);
    }

    $(function () {

        var gaugeOptions = {

            chart: {
                type: 'solidgauge'
            },

            title: null,

            pane: {
                center: ['50%', '50%'],
                size: '100%',
                startAngle: -180,
                endAngle: 180,
                background: {
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                    innerRadius: '80%',
                    outerRadius: '100%',
                    shape: 'arc'
                }
            },

            tooltip: {
                enabled: true
            },

            // the value axis
            yAxis: {
                stops: [
                    [0.1, '#17b1f7'], // red
                    [0.4, '#17b1f7'], // yellow
                    [0.6, '#17b1f7'], // green
                ],
                lineWidth: 0,
                minorTickInterval: null,
                tickPixelInterval: 400,
                tickWidth: 0,
                title: {
                    y: 50
                },
                labels: {
                    y: 0
                }
            },
            plotOptions: {
                solidgauge: {
                    innerRadius: '80%',
                    dataLabels: {
                        y: -25,
                        borderWidth: 0,
                        useHTML: true
                    }
                }
            }
        };
        // The speed gauge
        $('#totalWeightLoss').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 150,
                title: {
                    text: ''
                }
            },

            credits: {
                enabled: false
            },

            series: [{
                name: 'Total Weight Loss',
                data: [<?= $userDetails->total_weight_loss ?>],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                           '<span style="font-size:12px;color:silver">lbs</span></div>'
                },
                tooltip: {
                    valueSuffix: ' lbs'
                }
            }]

        }));

        // The speed gauge
        $('#weekWeightLoss').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 150,
                title: {
                    text: ''
                }
            },

            credits: {
                enabled: false
            },

            series: [{
                name: 'Week Weight Loss',
                data: [<?= $userDetails->week_weight_loss ?>],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                           '<span style="font-size:12px;color:silver">lbs</span></div>'
                },
                tooltip: {
                    valueSuffix: ' lbs'
                }
            }]

        }));

        // The speed gauge
        $('#monthWeightLoss').highcharts(Highcharts.merge(gaugeOptions, {
            yAxis: {
                min: 0,
                max: 150,
                title: {
                    text: ''
                }
            },

            credits: {
                enabled: false
            },
            series: [{
                name: 'Total Weight Loss',
                data: [<?= $userDetails->month_weight_loss ?>],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                        ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                           '<span style="font-size:12px;color:silver">lbs</span></div>'
                },
                tooltip: {
                    valueSuffix: ' lbs'
                }
            }]

        }));
    });
    
</script>
<script type="text/javascript">
    var site_url = '<?php echo $this->request->webroot; ?>';
        
    function profile_img_upload(obj,type)
    {
        //$('.loadingimg').css('display','inline-block');
        var input = $('#'+$(obj).attr('id'));//console.log(input.attr('id'));return false;
        
        $('<form enctype="multipart/form-data" method="post" id="upload_photo"></form>').append(input).hide().appendTo('body').ajaxForm({
            url: site_url+'users/before_after_upload',
            data: { imagename: input.attr('id')},
            success: function (data)
            {
                var data = jQuery.parseJSON(data);
                var file_element = $(document.createElement('input')).attr('type', 'file').attr('name', type+'_image').attr('id',input.attr('id')).attr('class',input.attr('class')).attr('onchange', 'profile_img_upload(this,"'+type+'")');
                $('.inputFile').prepend(file_element);
                if (data.status == 'success')
                { 
                    $('.showCropBox').click();
                    $('.img-container img').attr('src',data.image);
                    $('.img-preview img').attr('src',data.image);
                    $('#image-url').val(data.image);
                    $('#current-image').val(type);
                    //$('.imagecrop').attr('data-pid',data.image).attr('data-id','success');
                    //$('.loadingimg').css('display','none');
                }
                else
                {
                    //$('.loadingimg').css('display','none');
                    alert(data.message);
                }
            },
            complete:function()
            {
                //$('.loadingimg').css('display','none');
            }
        }).submit();
    }

$('document').ready(function()
{
    $('#saveMyPic').click(function(){
        var img_path = $('#image-url').val();
        var img_x = $('#image-x').val();
        var img_y = $('#image-y').val();
        var img_heigth = $('#image-height').val();
        var img_width = $('#image-width').val();
        var img_rotate = $('#image-rotate').val();
        var image_name = img_path.substring((img_path.lastIndexOf('/')+1), img_path.length);
        var type = $('#current-image').val();
                        
        $.ajax(
            {
                url: site_url+'users/cropnrotateBeforeAfter',
                data:{ image_name: image_name,img_x: img_x,img_y: img_y,img_heigth: img_heigth,img_width: img_width,img_width: img_width,img_rotate: img_rotate,type:type},
                success: function(response)
                {
                    var json_response = $.parseJSON(response);
                    if(json_response.status == 'success')
                    {
                        $('.'+type+'_image img').attr('src',json_response.image+'?rand='+Math.random());
                        //$('#imageResponse').hide();
                        $('#myModal').modal('hide');
                    }
                }
            });
    });
});
</script>