<?php
require __DIR__ . '/bootstrap.php';

$items = pdo()->query("SELECT * FROM items ORDER BY category, id")->fetchAll();

// Несколько последних отзывов для витрины
$reviews = pdo()->query(
    "SELECT r.rating, r.comment, u.fio, i.title
       FROM reviews r
       JOIN users u   ON u.id = r.user_id
       JOIN requests q ON q.id = r.request_id
       JOIN items i   ON i.id = q.item_id
      ORDER BY r.id DESC LIMIT 6"
)->fetchAll();

$totalRequests = (int) pdo()->query("SELECT COUNT(*) FROM requests")->fetchColumn();
$totalUsers    = (int) pdo()->query("SELECT COUNT(*) FROM users")->fetchColumn();

$PAGE_TITLE = 'Главная';
require __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO — кластер bento-тайлов ===== -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="container">
    <div class="bento bento-hero">
      <!-- Заголовок-тайл -->
      <div class="tile tile-headline reveal">
        <span class="hero-badge">✦ <?= e($CONFIG['tagline']) ?></span>
        <h1><?= e($CONFIG['hero_title']) ?></h1>
        <p><?= e($CONFIG['hero_text']) ?></p>
        <div class="hero-actions">
          <a href="<?= current_user() ? 'booking.php' : 'register.php' ?>" class="btn btn-primary btn-lg"><?= e($CONFIG['cta']) ?></a>
          <a href="#catalog" class="btn btn-ghost btn-lg">Смотреть каталог</a>
        </div>
      </div>

      <!-- CTA / мини-виджет «Быстрая бронь» -->
      <div class="tile tile-widget reveal">
        <div class="hero-card-glow"></div>
        <div class="mini-booking">
          <h3>Быстрая бронь</h3>
          <div class="mini-row"><span>📍 Локация</span><b>Москва, центр</b></div>
          <div class="mini-row"><span>🏷️ Категории</span><b><?= e(implode(' · ', $CONFIG['categories'])) ?></b></div>
          <div class="mini-row"><span>💳 Оплата</span><b>QR · МИР · офис</b></div>
          <a href="<?= current_user() ? 'booking.php' : 'register.php' ?>" class="btn btn-primary btn-block">Оформить заявку</a>
        </div>
      </div>

      <!-- Метрика-тайлы -->
      <div class="tile tile-metric reveal">
        <b data-count="<?= max(6, count($items)) ?>">0</b>
        <span><?= e($CONFIG['stat_items_label']) ?></span>
      </div>
      <div class="tile tile-metric reveal">
        <b data-count="<?= max(120, $totalRequests) ?>">0</b>
        <span><?= e($CONFIG['stat_requests_label']) ?></span>
      </div>
      <div class="tile tile-metric reveal">
        <b data-count="<?= max(340, $totalUsers) ?>">0</b>
        <span><?= e($CONFIG['stat_users_label']) ?></span>
      </div>
    </div>
  </div>
</section>

<!-- ===== КАТАЛОГ — мозаика bento-карточек ===== -->
<section class="section" id="catalog">
  <div class="container">
    <div class="section-head reveal">
      <h2><?= e($CONFIG['catalog_title']) ?></h2>
      <p><?= e($CONFIG['catalog_lead']) ?></p>
    </div>

    <div class="chips reveal" id="catFilter">
      <button class="chip active" data-cat="all">Все</button>
      <?php foreach ($CONFIG['categories'] as $cat): ?>
        <button class="chip" data-cat="<?= e($cat) ?>"><?= e($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="cards-grid bento bento-catalog" id="catalogGrid">
      <?php foreach ($items as $i => $it): ?>
        <article class="card tile<?= ($i % 5 === 0) ? ' tile-wide' : '' ?> reveal" data-cat="<?= e($it['category']) ?>" data-tilt>
          <div class="card-media">
            <img src="static/img/<?= e($it['image']) ?>" alt="<?= e($it['title']) ?>" loading="lazy">
            <span class="card-tag"><?= e($it['category']) ?></span>
          </div>
          <div class="card-body">
            <h3><?= e($it['title']) ?></h3>
            <p><?= e($it['description']) ?></p>
            <div class="card-meta">
              <span>👥 до <?= (int)$it['capacity'] ?> <?= e($it['meta']) ?></span>
              <span class="card-price"><?= money((int)$it['price']) ?> <small><?= e($CONFIG['price_unit']) ?></small></span>
            </div>
            <a href="<?= current_user() ? 'booking.php?item=' . (int)$it['id'] : 'register.php' ?>" class="btn btn-outline btn-block">Выбрать</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== КАК ЭТО РАБОТАЕТ — плитки-шаги ===== -->
<section class="section section-alt" id="how">
  <div class="container">
    <div class="section-head reveal">
      <h2>Как это работает</h2>
      <p><?= e($CONFIG['how_lead']) ?></p>
    </div>
    <div class="steps bento bento-steps">
      <?php foreach ($CONFIG['steps'] as $s): ?>
        <div class="step tile reveal" data-tilt>
          <div class="step-num"><?= $s[0] ?></div>
          <h3><?= e($s[1]) ?></h3>
          <p><?= e($s[2]) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== ОТЗЫВЫ — bento-плитки ===== -->
<?php if ($reviews): ?>
<section class="section" id="reviews">
  <div class="container">
    <div class="section-head reveal">
      <h2>Отзывы клиентов</h2>
      <p><?= e($CONFIG['reviews_lead']) ?></p>
    </div>
    <div class="reviews-grid bento bento-reviews">
      <?php foreach ($reviews as $i => $rv): ?>
        <figure class="review tile<?= ($i === 0) ? ' tile-wide' : '' ?> reveal" data-tilt>
          <div class="stars"><?= str_repeat('★', (int)$rv['rating']) . str_repeat('☆', 5 - (int)$rv['rating']) ?></div>
          <blockquote><?= e($rv['comment']) ?></blockquote>
          <figcaption><b><?= e($rv['fio']) ?></b><span><?= e($rv['title']) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== CTA — крупная плитка ===== -->
<section class="cta-band reveal">
  <div class="container cta-inner">
    <div>
      <h2><?= e($CONFIG['cta_title']) ?></h2>
      <p><?= e($CONFIG['cta_text']) ?></p>
    </div>
    <a href="<?= current_user() ? 'booking.php' : 'register.php' ?>" class="btn btn-light btn-lg"><?= e($CONFIG['cta']) ?></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
