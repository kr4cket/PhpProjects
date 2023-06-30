<?
    if (isset($data['errors'])) {
        foreach ($data['errors'] as $error) { ?>
        <h3><?=$error?></h3>
        <br>
    <? }
    }
?>
<form method="post">
    <p>Название товара</p>
    <input type='text' name="goodName" required value=<?= $data["goodName"];?>>
    <br>
    <p>Категория и производитель</p>
    <select name='typeList'>
        <option value='-1'>Тип товара</option>
        <? foreach ($data['typeList'] as $id => $value) {?>
            <option value=<?= $value['id']?>><?= $value['type_name']?></option>
        <?}?>
    </select>
    <br>
    <br>
    <select name='manufactureList'>
        <option value='-1'>Производитель</option>
        <? foreach ($data['manufactureList'] as $id => $value) {?>
            <option value=<?= $value['id']?>><?= $value['manufacture_name']?></option>
        <?}?>
    </select>
    <br>
    <p>Цена товара</p>
    <input type='text' name="goodCost" required value=<?= $data["goodCost"];?>>
    <br>
    <p>Описание товара</p>
    <input type='text' name="goodDescription" required value=<?= $data["goodDescription"];?>>
    <br>
    <input type="submit" value="Добавить">
</form>
