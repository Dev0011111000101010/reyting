<?php
global $user_ID, $user_LK;
$truthy                  = __( 'Практикує', 'referendum' );
$falsy                   = __( 'Практика відсутня', 'referendum' );
$usedata                 = get_userdata( $user_LK );
$name                    = $usedata->first_name ? $usedata->first_name : __( 'Не задано', 'referendum' );
$surname                 = $usedata->last_name ? $usedata->last_name : __( 'Не задано', 'referendum' );
$criminal                = [
	'practise'   => true ? $truthy : $falsy,
	'totalcount' => 12
];
//$criminalprofessional    = [
//	[
//		'article'    => 'Крадіжка п. 1 ст. 185',
//		'totalcount' => '10'
//	]
//];
$criminalprofessional    = [
	[
		'article'    => 'Крадіжка п. 1 ст. 185',
		'totalcount' => '10',
        'courts'=> [
                'Окружний адміністративний суд м.Києва'=>'2',
                'Печерський районний суд м.Києва' => '8'
        ]
	]
];
$civil                   = [
	'practise'   => true ? $truthy : $falsy,
	'totalcount' => 1
];
$civilprofessional       = [];
$admincupap              = [
	'practise'   => true ? $truthy : $falsy,
	'totalcount' => 2
];
$admincupapprofessional  = [];
$againsstate             = [
	'practise'   => true ? $truthy : $falsy,
	'totalcount' => 3
];
$againsstateprofessional = [];
$commercial              = [
	'practise'   => true ? $truthy : $falsy,
	'totalcount' => 4
];
$commercialprofessional  = [];
$casesbyyears            = [ 2017 => 2, 2018 => 20, 2019 => 3, 2020 => 11, 2021 => 1 ];
krsort( $casesbyyears, SORT_NUMERIC );
$bancruptpersons      = [ 'Фізичні особи' => 0, 'Фізичні особи -підприємці' => 3, 'Юридичні особи' => 0 ];
$lawyerssertificate   = [ 'active' => true, 'issuedate' => '03.02.2012' ];
$arbitragesertificate = [ 'active' => false, 'validstart' => '03.02.2012', 'validend' => '05.03.2019' ];
$privateexecutor      = [ 'active' => false ];
$professcourt         = 'Окружний адміністративний суд міста Києва';
?>
<div class="lawyer-profile">
    <h3><?php esc_html_e( 'Контактні дані', 'referendum' ); ?></h3>
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Ім\'я', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= $name ?>
        </div>
    </div>
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Прізвище', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= $surname ?>
        </div>
    </div>
    <hr>
    <h3><?php esc_html_e( 'Ліцензії', 'referendum' ); ?></h3>
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Посвідчення адвоката', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?php echo sertificateActivity( $lawyerssertificate ); ?>
        </div>
    </div>
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Арбітражний керуючий', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?php echo sertificateActivity( $arbitragesertificate ); ?>
        </div>
    </div>
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Приватний виконавець', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?php echo sertificateActivity( $privateexecutor ); ?>
        </div>
    </div>

    <hr>
    <h3><?php esc_html_e( 'Дані про діяльність адвоката', 'referendum' ); ?></h3>

    <!--    Кримінальні-->
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'В кримінальних справах', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= justiceSectorsWork( $criminal ); ?>
        </div>
    </div>
    <!--    Если участвовал в криминальных делах-->
	<?php if ( $criminal['practise'] == $truthy ) : ?>
		<?php $acquittals = 0;
		$savedyears       = 0;
		$wonmoney         = 0;
		?>
        <div class="lawyer-subtree">
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виправдальних вироків всього:', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= justiceSectorsWork( $acquittals ) ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Скільки років врятував адвокат:', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $savedyears ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виграно коштів (у вигляді компенсації і т.д):', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $wonmoney ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

    <!--    Адміністративні КАСУ-->
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Адміністративні КАСУ (проти органів влади)', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= justiceSectorsWork( $againsstate ) ?>
        </div>
        <!--    Если участвовал в админделах против государства-->
		<?php if ( $againsstate['practise'] == $truthy ) : ?>
			<?php $won = 0;
			$lost      = 0;
			$wonmoney  = 0;
			?>
            <div class="lawyer-subtree">
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Виграно справ: ', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $won ?>
                    </div>
                </div>
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Програно справ:', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $lost ?>
                    </div>
                </div>
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Виграно коштів (у вигляді компенсації і т.д):', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $wonmoney ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>

    <!--    Цивільні-->
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Цивільні', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= justiceSectorsWork( $civil ) ?>
        </div>
        <!--    Если участвовал в гражданских делах-->
		<?php if ( $civil['practise'] == $truthy ) : ?>
			<?php $won = 0;
			$lost      = 0;
			$wonmoney  = 0;
			?>
            <div class="lawyer-subtree">
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Виграно справ: ', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $won ?>
                    </div>
                </div>
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Програно справ:', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $lost ?>
                    </div>
                </div>
                <div class="lawyer-profile-field">
                    <div class="lawyer-field-label">
						<?php esc_html_e( 'Виграно коштів (у вигляді компенсації і т.д):', 'referendum' ); ?>
                    </div>
                    <div class="lawyer-field-value">
						<?= $wonmoney ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>

    <!--    Адмінправопорушення КОАПП-->
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Щодо адміністративних правопорушень (КУпАП)', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= justiceSectorsWork( $admincupap ) ?>
        </div>
    </div>
    <!--    Если участвовал в Админ преступлениях КОАПП-->
	<?php if ( $admincupap['practise'] == $truthy ) : ?>
		<?php $acquittals = 0;
		$savedyears       = 0;
		$wonmoney         = 0;
		?>
        <div class="lawyer-subtree">
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виправдальних вироків всього:', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $acquittals ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Скільки років врятував адвокат:', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $savedyears ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виграно коштів (у вигляді компенсації і т.д):', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $wonmoney ?>
                </div>
            </div>
        </div>
	<?php endif; ?>

    <!--    Господарські-->
    <div class="lawyer-profile-field">
        <div class="lawyer-field-label">
			<?php esc_html_e( 'Господарські', 'referendum' ); ?>
        </div>
        <div class="lawyer-field-value">
			<?= justiceSectorsWork( $commercial ) ?>
        </div>
    </div>
    <!--    Если рассматривал хозяйственные дела-->
	<?php if ( $commercial['practise'] == $truthy ) : ?>
		<?php $bankruptcy = true ? $truthy : $falsy; ?>
        <div class="lawyer-subtree">
			<?php $won = 0;
			$lost      = 0;
			$wonmoney  = 0;
			?>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виграно справ: ', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $won ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Програно справ:', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $lost ?>
                </div>
            </div>
            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Виграно коштів (у вигляді компенсації і т.д):', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $wonmoney ?>
                </div>
            </div>

            <div class="lawyer-profile-field">
                <div class="lawyer-field-label">
					<?php esc_html_e( 'Участь в справах, пов\'язаних з банкрутством', 'referendum' ); ?>
                </div>
                <div class="lawyer-field-value">
					<?= $bankruptcy ?>
                </div>

                <!--    Если занимался банкротством-->
				<?php if ( $bankruptcy == $truthy ) : ?>
					<?php
					$debttotal = 0;
					?>
                    <div class="lawyer-subtree">
                        <div class="lawyer-profile-field">
                            <div class="lawyer-field-label">
								<?php esc_html_e( ' Категорія суб\'єктів з неплатоспроможності (банкрутства)', 'referendum' ); ?>
                            </div>
                            <div class="lawyer-field-value w-100">
                                <!--    Если есть виды особ-банкротов-->
								<?php if ( is_array( $bancruptpersons ) ) : ?>
									<?php foreach ( $bancruptpersons as $key => $value ) : ?>
										<?= $key ?> (<?= $value ?>) <br>
									<?php endforeach; ?>
								<?php else: ?>
									<?php esc_html_e( 'Не вказано', 'referendum' ); ?>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="lawyer-profile-field">
                            <div class="lawyer-field-label">
								<?php esc_html_e( 'Cума боргу', 'referendum' ); ?>
                            </div>
                            <div class="lawyer-field-value">
								<?= $debttotal ?>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
        </div>

	<?php endif; ?>

    <hr>
    <h3><?php esc_html_e( ' Досвід у конкретному суді', 'referendum' ); ?></h3>
	<?php if ( $professcourt ) : ?>
        <div class="lawyer-profile-field">
            <div class="lawyer-field-label">
				<?php esc_html_e( 'Професіонал по суду (найбільше виграних справ)', 'referendum' ); ?>
            </div>
            <div class="lawyer-field-value">
				<?= $professcourt ?>
            </div>
        </div>
	<?php endif; ?>
    <hr>
    <h3><?php esc_html_e( 'Професіонал по видам справ', 'referendum' ); ?></h3>
	<?php if ( $criminal['practise'] == $truthy ) : ?>
        <div class="lawyer-profile-field">
            <div class="lawyer-field-label">
				<?php esc_html_e( 'Кримінальні', 'referendum' ); ?> (<?= $criminal['totalcount'] ?>)
            </div>
            <div class="lawyer-field-value">
                <?= professionalSector($criminalprofessional); ?>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( $againsstate['practise'] ) : ?>
        <div class="lawyer-profile-field">
            <div class="lawyer-field-label">
				<?php esc_html_e( 'Адміністративні КАСУ (проти органів влади)', 'referendum' ); ?>
                (<?= $againsstate['totalcount'] ?>)
            </div>
            <div class="lawyer-field-value">
	            <?= professionalSector($againsstateprofessional); ?>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( $civil['practise'] ) : ?>
        <div class="lawyer-profile-field">
            <div class="lawyer-field-label">
				<?php esc_html_e( 'Цивільні', 'referendum' ); ?> (<?= $civil['totalcount'] ?>)
            </div>
            <div class="lawyer-field-value">
	            <?= professionalSector($civilprofessional); ?>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( $admincupap['practise'] ) : ?>
        <div class="lawyer-profile-field">
            <div class="lawyer-field-label">
				<?php esc_html_e( 'Щодо адміністративних правопорушень (КУпАП)', 'referendum' ); ?>
                (<?= $admincupap['totalcount'] ?>)
            </div>
            <div class="lawyer-field-value">
	            <?= professionalSector($admincupapprofessional); ?>
            </div>
        </div>
	<?php endif; ?>
    <hr>
    <h3><?php esc_html_e( 'Кількість активних судових справ' ); ?></h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col"><?php esc_html_e( 'Рік', 'referendum' ); ?></th>
            <th scope="col"><?php esc_html_e( 'К-сть справ', 'referendum' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $casesbyyears as $year => $value ) : ?>
            <tr>
                <th scope="row"><?= $year ?></th>
                <td><?= $value ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>