<? foreach ($data['pages'] as $page) {?>
    <?if ($page != '...') {?>
        <a href="?page=<?=$page.$data['link']?>"><?=$page?></a>
    <?}else {?>
        <a><?=$page?></a>
<?}}?>