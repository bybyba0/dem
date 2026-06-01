<?php global $CONFIG; ?>
</main>

<footer class="site-footer" id="contacts">
  <div class="container footer-grid">
    <div class="footer-col">
      <div class="logo logo--footer">
        <!-- <span class="logo-icon"><?= $CONFIG['brand_icon'] ?></span> -->
        <span class="logo-text"><?= e($CONFIG['brand']) ?></span>
      </div>
      <p class="footer-tagline"><?= e($CONFIG['tagline']) ?></p>
    </div>

    <div class="footer-col">
      <h4>Контакты</h4>
      <p>Наш адрес:  <?= e($CONFIG['address']) ?></p>
      <p>Телефон:  <a href="tel:<?= preg_replace('/[^+\d]/', '', $CONFIG['phone']) ?>"><?= e($CONFIG['phone']) ?></a></p>
      <p class="muted-text">Если возникли вопросы или пожелания — позвоните нам. Ответим оперативно и подробно.</p>
    </div>

    <div class="footer-col">
      <h4>Способы оплаты</h4>
      <ul class="pay-list">
        <?php foreach ($CONFIG['payments'] as $p): ?>
          <li><?= e($p) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="footer-bottom container">
    © <?= date('Y') ?> <?= e($CONFIG['brand']) ?>. Демонстрационный экзамен.
  </div>
</footer>

<button class="to-top" id="toTop" aria-label="Наверх">↑</button>

<script src="static/app.js"></script>
<script src="static/variant.js"></script>
</body>
</html>
