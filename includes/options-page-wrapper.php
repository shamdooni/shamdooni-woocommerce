<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1  class="shamduni">شمعدونی</h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle "><span class="shamduni">تنظیمات</span>
						</h2>
						<div class="inside">
								<?php
								    global $wc_shamdooni_address;
									$options = get_option('shamduni_rounder');    								
									$response = wp_remote_post( $wc_shamdooni_address . '/api/rounder/v1/getBalance' , 
										array(
											'method' => 'POST',
											'timeout' => 5,
											'redirection' => 5,
											'httpversion' => '1.0',
											'blocking' => true,
											'headers' => array(),
											'body' => array( 
												'key' => $options['shamduni_apikey']
											),
											'cookies' => array()
										) 
									);
									if($response['response']['code'] == 200) {
										$balance = json_decode($response['body']);
										
										echo '<h2>'.'بدهی شما: ' . $balance->{'balance'} . ' ریال'.'</h2>';
										echo '<hr>';
									}
								?>
                            <form method="post" action="">
								<input type="hidden" value="Y" name="shamduni_form_submitted">
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">API Key</td>
                                        <td><input name="shamduni_apikey" id="shamduni_apikey" type="text" value="<?php echo $shamduni_apikey ?>" class="regular-text" /></td>
                                    </tr>
                                </table>
                                <p>
                                    <input class="button-primary" type="submit" name="shamduni_apykey" class="shamduni" value="ذخیره" />
                                </p>
                            </form>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->