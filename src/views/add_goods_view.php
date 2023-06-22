<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Добавить новый товар</title>
</head>
<body>
    <? 
        if (isset($data['errors'])) {
            foreach ($data['errors'] as $error) { ?>
            <h3><?=$error?></h3>
            <br>
        <? }} ?>
    <form method="post">
        <input type='text' name="goodName" required value=<?= $data["goodName"];?>>
        <br>
        <select name='typeList'>
            <option value='-1'>Тип товара</option>
            <? foreach ($data['typeList'] as $id => $value) {?>
                <option value=<?= $value['id']?>><?= $value['type_name']?></option>
            <?}?>
        </select>
        <br>
        <select name='manufactureList'>
            <option value='-1'>Производитель</option>
            <? foreach ($data['manufactureList'] as $id => $value) {?>
                <option value=<?= $value['id']?>><?= $value['manufacture_name']?></option>
            <?}?>
        </select>
        <br>
        <input type='text' name="goodCost" required value=<?= $data["goodCost"];?>>
        <br>
        <input type='text' name="goodDescription" required value=<?= $data["goodDescription"];?>>
        <br>
        <input type="submit" value="Добавить">
    </form>
</body>
</html>