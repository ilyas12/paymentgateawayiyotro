<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Really Simple HTML Email Template</title>
<style>
/* -------------------------------------
    GLOBAL
------------------------------------- */
* {
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}

img {
  max-width: 600px;
  width: auto;
}

body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}


/* -------------------------------------
    ELEMENTS
------------------------------------- */
a {
  color: #348eda;
}

.btn-primary {
  Margin-bottom: 10px;
  width: auto !important;
}

.btn-primary td {
  background-color: #348eda; 
  border-radius: 25px;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
  font-size: 14px; 
  text-align: center;
  vertical-align: top; 
}

.btn-primary td a {
  background-color: #348eda;
  border: solid 1px #348eda;
  border-radius: 25px;
  border-width: 10px 20px;
  display: inline-block;
  color: #ffffff;
  cursor: pointer;
  font-weight: bold;
  line-height: 2;
  text-decoration: none;
}

.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.padding {
  padding: 10px 0;
}


/* -------------------------------------
    BODY
------------------------------------- */
table.body-wrap {
  padding: 20px;
  width: 100%;
}

table.body-wrap .container {
  border: 1px solid #f0f0f0;
}


/* -------------------------------------
    FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;  
}

.footer-wrap .container p {
  color: #666666;
  font-size: 12px;
  
}

table.footer-wrap a {
  color: #999999;
}


/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, 
h2, 
h3 {
  color: #111111;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 40px 0 10px;
}

h1 {
  font-size: 36px;
}
h2 {
  font-size: 28px;
}
h3 {
  font-size: 22px;
}

p, 
ul, 
ol {
  font-size: 14px;
  font-weight: normal;
  margin-bottom: 10px;
}

ul li, 
ol li {
  margin-left: 5px;
  list-style-position: inside;
}

/* ---------------------------------------------------
    RESPONSIVENESS
------------------------------------------------------ */

/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  Margin: 0 auto !important;
  max-width: 600px !important;
}

/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}

/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  margin: 0 auto;
  max-width: 600px;
}

/* Let's make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

</style>
</head>

<body bgcolor="#f6f6f6">

<!-- body -->
<table  bgcolor="#f6f6f6" width="100%">
  <tr>
    <td></td>
    <td class="container" bgcolor="#FFFFFF">

      <!-- content -->
      <div class="content">
      <table width="100%">
        <tr>
          <td>
            <h2>Payment Getway</h2>
            <p>Forum : <?=$data['topic']?></p>
            <p>Status : <?=$data['forum_stat']==9?'CLOSED':'OPEN'?></p>
            <p>Hi <?=isset($data['to_name'])?$data['to_name']:''?>.</p>
            <!-- button -->
            <table  cellpadding="0" cellspacing="0" border="0" style="padding-left: 3px;" width="100%">
              <!--tr style="background-color: #cccccc;">
                <td align="left" style="border-top:0.5px solid #000000;">
                  <span  style="font-weight: bold;font-size: 12px;"><?=$data['member']?></span><br>
                  <span style="font-size: 10px;" >Member</span>
                </td>
                <td align="right" style="border-top:0.5px solid #000000;">
                  <span  style="font-size: 12px;"><?=date('H:i d/m/Y', strtotime($data['created_date']))?></span><br>
                </td>
              </tr>
              <tr>
                <td colspan="2" style="border-top:0.5px solid #cccccc;border-bottom:0.5px solid #000000;">
                  <div style="font-weight: bold;font-size: 14px;"><?=$data['topic']?></div>
                  <div class=" font-bold "><?=$data['topic_detail']?></div>
                </td>
              </tr-->
              <?php foreach ($data['detail'] as $key => $value) { ?>
                <tr style="background-color: #cccccc;">
                  <td align="left" style="border-top:0.5px solid #000000;">
                    <?php if($value['type'] == 'member'){ ?>
                      <span  style="font-weight: bold;font-size: 12px;"><?=$value['member']?></span><br>
                      <span style="font-size: 10px;" >Member</span>
                    <?php }else{ ?>
                      <span  style="font-weight: bold;font-size: 12px;"><?=$value['admin']?></span><br>
                      <span style="font-size: 10px;" >Admin</span>
                    <?php } ?>
                  </td>
                  <td align="right" style="border-top:0.5px solid #000000;">
                    <span  style="font-size: 12px;"><?=date('H:i d/m/Y', strtotime($data['created_at']))?></span><br>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="border-top:0.5px solid #cccccc;border-bottom:0.5px solid #000000;">
                    <div class=" font-bold "><?=$value['reply_content']?></div>
                  </td>
                </tr>

              <?php } ?>
            </table>
            <br>
            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
            <p>Conclusion : <?=$data['admin_conclusion']?></p>
            <br>
            <br>
            <!-- /button -->
          </td>
        </tr>
      </table>
      </div>
      <!-- /content -->
      
    </td>
    <td></td>
  </tr>
</table>
<!-- /body -->

<!-- footer -->
<table class="footer-wrap">
  <tr>
    <td></td>
    <td class="container">
      
      <!-- content -->
      <div class="content">
        <table>
          <tr>
            <td align="center">
              <p>Payment Getway .
              </p>
            </td>
          </tr>
        </table>
      </div>
      <!-- /content -->
      
    </td>
    <td></td>
  </tr>
</table>
<!-- /footer -->

</body>
</html>
