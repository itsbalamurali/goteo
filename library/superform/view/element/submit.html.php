<?php
?>
<input type="submit" name="<?php echo htmlspecialchars($this['name']) ?>" value="<?php echo htmlspecialchars($this['label']) ?>" class="<?php echo htmlspecialchars($this['class']) ?>" <?php if (!empty($this['disabled'])) echo 'disabled="' . htmlspecialchars($this['disabled']) . '"' ?>/>