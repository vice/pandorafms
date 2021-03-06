<?php
/**
 * @package Include/help/ja
 */
?>

<h1>サーバフィールド</h1>

"サーバ(server)"フィールドでは、監視を行うサーバを選択することができます。

サーバの設定
<br><br>
サーバには、2つの動作モードがあります。
<br><br>
<ul>
<blockquote>
<li>マスターモード
<li>非マスターモード
</ul>

<br>
重要な点として、同じ種類(例:ネットワークサーバ)の複数のサーバがある場合、HA モードで動作する必要があります。一台のサーバで障害が発生した場合、マスターサーバはダウンしたサーバが実行しようとしていたネットワークモジュールの実行を引き継ぎます。非マスターサーバはそのような動作は行いません。
<br><br>
このオプションは、/etc/pandora/pandora_server.conf ファイル内の master 1 という設定で行います。
<br><br><i>
master 1
<br><br></i>
1を設定すると有効になり、0であれば無効になります。
