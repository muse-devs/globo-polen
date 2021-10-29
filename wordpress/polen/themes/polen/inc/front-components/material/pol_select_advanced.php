<div id="<?php echo $id; ?>" class="select-advanced">
  <?php foreach ($items as $item) : ?>
    <label class="item<?php echo $item["checked"] ? ' -checked' : ''; ?><?php echo $item["disabled"] ? ' -disabled' : ''; ?>">
      <input type="radio" name="<?php echo $name; ?>" value="<?php echo $item['value']; ?>" <?php echo $item["checked"] ? " checked" : ""; ?><?php echo $item["disabled"] ? " disabled" : ""; ?> />
      <?php if ($item["icon"]) : ?>
        <figure class="icon">
          <img src="<?php echo $item["icon"]; ?>" alt="">
        </figure>
      <?php endif; ?>
      <span><?php echo $item["title"]; ?></span>
    </label>
  <?php endforeach; ?>
</div>
<script>
  const component = document.querySelector("#<?php echo $id; ?>");
  const radio = document.querySelectorAll("input[name=<?php echo $name; ?>]");

  function removeChecked() {
    const items = document.querySelectorAll("#<?php echo $id; ?> .item");
    [...items].map(item => item.classList.remove("-checked"));
  }
  [...radio].map(item => {
    item.addEventListener("click", function(e) {
      removeChecked();
      this.parentNode.classList.add("-checked");
      component.dispatchEvent(new CustomEvent("pol-select-change", {
        detail: e.target.value
      }));
    });
  });
</script>
