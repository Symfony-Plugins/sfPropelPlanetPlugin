<div id="sf_planet_admin">
  
  <h1><?php echo __('Add a feed from its url') ?></h1>
  
  <p>
    <?php echo __('Enter an RSS or Atom feed url in the form below to add it to your planet.') ?>
    <?php echo __('All the feed properties will be automatically retrieved from there.') ?>
  </p>
  
  <?php include_partial('sfPlanetAdmin/messages') ?>
  
  <form action="<?php echo url_for('sfPlanetAdmin/createFromFeedUrl') ?>" method="post">
    <table>
      <tbody>
        <?php echo $form->renderGlobalErrors() ?>
        <tr>
          <th><label for="sf_planet_feed_url"><?php echo __('Feed url') ?></label></th>
          <td>
            <?php echo $form['feed_url']->renderError() ?>
            <?php echo $form['feed_url']->render(array('size' => 50)) ?>
          </td>
        </tr>
        <tr>
          <th><label for="sf_planet_feed_periodicity"><?php echo __('Peremption time') ?></label></th>
          <td>
            <?php echo $form['periodicity']->renderError() ?>
            <?php echo $form['periodicity'] ?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td>
            <?php echo $form['activate']->renderError() ?>
            <?php echo $form['activate'] ?>
            <label for="sf_planet_feed_activate"><?php echo __('Activate feed?') ?></label>
          </td>
        </tr>
        <tr>
          <th></th>
          <td>
            <?php echo $form['fetch']->renderError() ?>
            <?php echo $form['fetch'] ?>
            <label for="sf_planet_feed_fetch"><?php echo __('Fetch entries?') ?></label>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td>
            <?php echo link_to(__('Cancel|Back to list'), 'sfPlanetAdmin/index') ?>
          </td>
          <td>
            <?php echo $form['_csrf_token'] ?>
            <input type="submit" value="<?php echo __('Get the feed and save it') ?>" />
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  
</div>