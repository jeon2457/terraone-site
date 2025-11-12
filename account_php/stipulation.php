<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 부트스트랩 5.3 CDN -->
  <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
      crossorigin="anonymous"
    />
    <!-- 부트스트랩 5.3 스크립트 (defer: html을 모두 읽고나서 스크립트를 처리하라) -->
    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
      crossorigin="anonymous"
    ></script>
    <!-- 공통 스타일시트 연결 -->
    <link rel="stylesheet" href="../css/common.css">
    <!-- main.php 고유의 스타일시트 연결 -->
    <link rel="stylesheet" href="../css/main.css">

    <link rel="stylesheet" type="text/css" href="../css/style.css" />
  
</head>

<?php 

// 여기서 $js_array를 사용하는 이유는 js폴더내의 자바스크립트 파일중에서 어떤것을 선택하기위함이다.
$js_array = ['js/member.js']; //member.js파일을 include한다

$g_title = '약관';
$menu_code = 'member'; // 네비바 항목별색상 active부여!

include "inc_header.php"; 



?>

      <main class="p-5 border rounded-5">
        <h1 class="text-center">회원 약관 및 개인정보 취급방침 동의</h1>
        <h4>회원 약관</h4>
        <textarea name="" id="" cols="30" rows="10" class="form-control">
          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Itaque aliquid vitae tenetur placeat esse. Impedit expedita dolor enim saepe pariatur dolores repellendus, repellat magnam possimus nobis placeat voluptates optio necessitatibus?
          Voluptas itaque incidunt ullam minima ea, dolore quam harum obcaecati, quidem nostrum nemo quasi praesentium aliquam quis consequuntur earum distinctio dicta amet unde cupiditate quod odit. Incidunt, quis. Facere, fuga!
          Animi, facilis dicta sequi, quidem ea iusto cupiditate fugiat velit, eaque in exercitationem cumque dolore voluptatum ex. Labore maiores eaque magnam sit tenetur earum, veniam esse incidunt cupiditate quas numquam.
          Magnam unde quae eos, illo beatae id quo vitae impedit! Quod incidunt ullam alias veniam consequatur nihil magni qui cupiditate odio omnis debitis eaque, autem sit esse voluptatem praesentium deserunt?
          Labore necessitatibus ullam quasi, nemo, voluptatem nam amet velit deleniti excepturi atque maiores, libero delectus modi consectetur obcaecati quisquam. Voluptates fuga nesciunt similique praesentium eum esse iure alias veritatis quibusdam.
          Corporis iure optio harum magni doloremque, dolores rem blanditiis debitis, totam sed esse itaque modi nobis? Repellendus neque quae repudiandae natus totam hic facere praesentium pariatur accusantium, dicta quam itaque.
          Earum ex natus mollitia laboriosam laborum repellat voluptatum atque provident fugiat ipsam laudantium nobis repudiandae, et illo quidem deleniti pariatur delectus adipisci incidunt enim officia velit. Neque assumenda exercitationem consequatur?
          Corrupti, cum quidem quod in sit sint repellat repudiandae laudantium qui accusantium eligendi magnam voluptates tenetur esse nobis eius? Suscipit, nisi temporibus sunt incidunt expedita dicta voluptatem minima tenetur adipisci!
          Adipisci provident non dignissimos eligendi quia voluptatum impedit ducimus dolor placeat harum. Beatae ratione minima obcaecati, molestias odit at fugiat ea, est voluptate amet minus rerum illum quis, quo omnis.
          Quas laudantium, maxime repellendus praesentium illo, nobis porro expedita quos alias consequatur placeat magni culpa commodi recusandae! Soluta quidem culpa quia ipsum facere illum adipisci optio molestiae, minima expedita voluptas.
          Quo dolor autem reiciendis quisquam illo hic. Tempore quidem ipsum optio alias officia accusantium odio, deserunt sint incidunt, aliquam, neque voluptatem aliquid? Perferendis eligendi, est voluptatum mollitia blanditiis dicta sunt?
          Nulla, qui non. Magni nam placeat autem? Delectus sit numquam ex aliquam laborum architecto ducimus, vitae eligendi vero iste nam laboriosam explicabo, tenetur at rem aut in, minima eaque adipisci.
          Repellendus, amet! Facilis officiis pariatur corrupti laboriosam exercitationem maxime omnis, fugit aspernatur. Sequi iure quam molestias reprehenderit. Numquam pariatur laboriosam eveniet, iusto, ad, deserunt architecto impedit eligendi molestiae facilis voluptatibus?
          A mollitia veniam inventore neque reprehenderit ipsam esse at. Ab illum fugiat tempore sequi repellendus minus provident quidem magnam non velit! Fuga dignissimos cum unde pariatur velit esse labore quod!
          Blanditiis quae aliquid tempora labore error voluptatum, tenetur facere fugiat esse quis ullam fugit minus, commodi est deleniti possimus consectetur iste earum reiciendis perferendis expedita necessitatibus! Nesciunt deleniti at voluptates?
          Laborum quae totam harum praesentium tempore. Consequuntur quo quibusdam voluptatum mollitia doloribus praesentium, velit a ducimus fuga amet reprehenderit consequatur, minima, corporis sequi assumenda quisquam pariatur itaque eos dolore. Iste.
          A cum fuga possimus commodi omnis debitis, quo deleniti sequi cupiditate quaerat amet deserunt maxime cumque tempora, praesentium, voluptatem rerum dolor! Veritatis eum reprehenderit cum placeat molestias quaerat minima sit!
          Provident temporibus iste esse ratione quo odit! Harum iste omnis distinctio corrupti in nihil consectetur minima maxime odit architecto vitae, quas atque expedita itaque temporibus hic consequuntur animi soluta natus.
          Voluptatibus expedita, minus totam, est numquam unde nam dolorum soluta doloribus et at architecto aspernatur sint fugit sed dolorem deserunt eum modi. Labore non at dolorem provident esse, repellendus veniam?
          Maiores vero excepturi architecto, quidem impedit officiis labore minima, recusandae nemo laudantium voluptate velit laboriosam quas consectetur officia eos, nisi placeat nesciunt enim. Modi nihil adipisci repudiandae nam mollitia officia.
          Laborum illum quasi iusto. Optio molestias sint libero unde alias consequuntur exercitationem, quibusdam delectus ad placeat, impedit ab quia et culpa rem, officia saepe. Alias, incidunt. Libero, doloremque sapiente! Debitis!
          Magni repellat similique dolorem non nemo, cupiditate iste doloribus assumenda quod mollitia impedit aut maiores veritatis quas qui hic asperiores, suscipit totam voluptas ducimus dignissimos nihil debitis quisquam! Eius, veritatis!
          Totam eum nam similique officia corrupti optio nihil aliquam odio, ratione sunt modi dolores voluptatem iusto inventore ut quas tempore eos quae, soluta voluptatibus dicta iste possimus. Reprehenderit, velit. Exercitationem.
          Perspiciatis laudantium sit est quaerat incidunt? Saepe, natus aperiam magnam officiis omnis ducimus ab ratione. Est quas, obcaecati possimus velit commodi dicta ut eveniet maxime aut accusantium officia consectetur omnis!
          Corporis saepe sint rem accusantium magni architecto minima eligendi provident, cum est molestias fuga repellat quae soluta beatae, nemo asperiores voluptate similique expedita dolores error molestiae quam maxime delectus? Quos.
          Quos aliquid debitis vitae mollitia vero corporis, neque magni asperiores voluptatibus odio, vel ipsam totam repudiandae numquam nihil voluptatum maxime? Sint illo incidunt aspernatur harum ipsa, velit asperiores delectus numquam.
          Inventore voluptatem voluptates ipsa non eligendi et ut! Possimus neque accusamus sint hic veniam nostrum harum numquam esse! Sint sequi corporis quae obcaecati, ratione laboriosam consequuntur pariatur quam commodi esse?
          At blanditiis itaque consequatur ducimus distinctio veritatis odio quas porro culpa, rem reiciendis quam nesciunt quibusdam, sint hic exercitationem veniam enim dicta quo asperiores natus voluptas ex. Facilis, aspernatur reiciendis.
          Corrupti, dignissimos necessitatibus omnis a velit enim quo amet natus modi doloremque pariatur consequuntur. Iusto dicta odit sapiente fuga, consequatur iste. In odio impedit dolore pariatur eveniet, modi reprehenderit excepturi!
          Asperiores corporis hic nulla debitis maxime veritatis iste, perferendis similique assumenda pariatur ipsam ipsa laboriosam! Autem voluptatibus quae eum vero natus quos magnam, laboriosam sit reprehenderit optio architecto provident eaque?
          Quisquam deserunt suscipit similique, dolorem excepturi ipsa iure inventore sapiente ipsam, nam, dolorum corporis consectetur illum nihil recusandae cupiditate architecto tempora! Molestiae et explicabo eos saepe delectus quisquam exercitationem temporibus!
          Ab corrupti perferendis maxime esse unde delectus provident nulla, praesentium impedit aliquid dolor modi sed vitae odio incidunt enim corporis minima cumque. Optio, deserunt necessitatibus qui pariatur tempore quos quisquam!
          Quaerat doloribus eligendi temporibus veniam hic dicta alias voluptate labore perspiciatis vel magnam sequi, quisquam cum voluptas officiis enim ducimus omnis sunt placeat explicabo? Numquam ducimus ex quibusdam voluptatem eos!
          Quisquam nihil recusandae eaque ipsa voluptatibus a rem iure saepe! Odit corrupti id esse voluptatum. Laudantium, dolorum dignissimos aspernatur eveniet fugiat necessitatibus odio facilis porro explicabo numquam. Atque, qui laboriosam.
          Esse a laboriosam neque culpa sequi similique, optio non laborum doloribus quasi molestiae ex ratione perferendis architecto quibusdam eius molestias numquam pariatur enim itaque! Tempora distinctio corporis voluptatem quae facilis?
          Temporibus, ea assumenda aliquid ab aliquam, saepe ipsam est cum, nihil velit sapiente. Accusamus, dolores, alias repudiandae id consequatur reiciendis maxime molestiae ipsa exercitationem facere minima incidunt quae numquam dignissimos.
          onem culpa enim aut, tenetur adipisci sit nobis! Tempora voluptatem veniam quidem, minus asperiores error doloribus exercitationem ratione quo, molestias dolor aspernatur consequuntur magni illum! Consequatur, earum.
          Enim quisquam modi iusto maxime adipisci dolores praesentium labore, voluptatum, possimus accusantium molestiae blanditiis illo eos reprehenderit quod accusamus aspernatur tempore? At, enim rem quia nisi itaque explicabo aliquam illum!
          Officia sed sunt voluptatem dignissimos excepturi ipsa! Non reiciendis unde odio rem incidunt, voluptatum, at debitis eligendi maiores amet, consequuntur corrupti dignissimos. Sapiente, pariatur quibusdam. Totam optio sunt voluptates veritatis.
          Delectus nihil repellendus, eos quidem praesentium ducimus vero labore error et voluptatum itaque, saepe est ipsum asperiores dolor. Commodi facere officiis minima soluta rem atque. Debitis ea animi architecto dolore.
          Provident fugit distinctio ex porro dolore aperiam rem, dignissimos voluptatem aspernatur explicabo quo officiis deleniti, dolorem, itaque dolores. Soluta obcaecati pariatur rerum amet facilis ut quidem vero! Nemo, illum magni!
          Neque ad doloribus ipsam numquam voluptatem, inventore fuga nemo minus id recusandae corrupti nostrum at! Nulla incidunt fuga tempora atque, perspiciatis aut? Modi repudiandae eius placeat facere, debitis tempora amet!
          Magnam enim, dolorem eaque officiis iste similique vero doloribus sequi iure harum ipsum impedit omnis, nisi hic ullam quam animi consequuntur, saepe sit aliquam. Molestias alias numquam mollitia quam eligendi.
          Tempore, voluptatum quibusdam quas non officiis obcaecati accusamus eligendi. Optio iusto tempore, ea natus tenetur libero rem consequuntur iste earum maiores, non quis velit aperiam animi soluta explicabo omnis. Ex.
          perferendis quisquam, et quo illo, provident distinctio, facilis id harum ratione quod magnam! Accusantium consectetur molestiae modi nihil explicabo? Fugiat voluptas, adipisci mollitia ipsa quod vel.
          itatis mollitia ipsa, eius laborum molestiae culpa corporis repellat aspernatur id itaque in? Sunt iure reiciendis, modi quibusdam illum vero at incidunt tempora.
          Impedit, pariatur fugit recusandae iure neque ipsam reprehenderit? Illo odit alias labore adipisci eius ea eligendi omnis fugiat perferendis natus cum rem ullam nulla doloribus suscipit praesentium, quaerat totam nihil?
          Distinctio magni eos quibusdam. Fuga est necessitatibus, deleniti dolores non, nostrum facere mollitia explicabo velit cum rem sit maiores pariatur enim, tempora natus! Maxime eum ea qui assumenda vitae magnam.
          Fugit animi similique maxime adipisci sequi ex cum eaque tempore obcaecati perspiciatis voluptatibus, velit distinctio totam vel, neque quibusdam? Quisquam ut fuga consequatur soluta impedit repellendus quas ab aspernatur veniam.
          A repellat commodi consequatur architecto magnam optio nam, aut blanditiis. Fuga expedita nemo officia enim perferendis quis sapiente, corporis totam pariatur! Veritatis omnis officiis dolore voluptatem, sapiente veniam nemo exercitationem!
          Explicabo sit veniam beatae at quidem deserunt autem impedit consequuntur dolor quia labore iusto accusantium assumenda ullam, nulla minus nesciunt quisquam magni fugiat pariatur nobis porro? Minima maiores debitis quis.
          Iure ratione, laborum aspernatur eum consectetur adipisci minus labore consequatur et quas accusamus reiciendis alias velit similique natus ipsa rerum, cumque expedita harum nobis tempora culpa quisquam aliquid error. Voluptate.
          Ad earum reprehenderit alias sunt vero voluptatum impedit, illum nihil, eos, unde commodi quam optio necessitatibus iusto recusandae natus laborum voluptates pariatur quis mollitia. Perspiciatis illum animi temporibus necessitatibus dolores.
          Quas et porro eligendi culpa. Ad itaque quaerat debitis. Doloribus necessitatibus, tempore nostrum blanditiis nihil iure labore numquam a sunt nisi maiores, animi placeat vitae vel iusto? Quidem, nesciunt beatae.
        </textarea>

        <!-- 체크박스 -->
        <div class="form-check mt-2">
          <input
            class="form-check-input"
            type="checkbox"
            value="1"
            id="chk_member1"
          />

          <!-- 구분하기위해 id값 지정 아래 for값도 동일하게 부여-->
          <!-- 동의하시겠습니까? 라는 텍스트를 클릭했을때도 체크가 되게하기위해서다. -->
          <label class="form-check-label" for="chk_member1">
            위 약관에 동의하시겠습니까?
          </label>
        </div>

        <h4 class="mt-3">개인정보 취급방침</h4>

        <textarea name="" id="" cols="30" rows="10" class="form-control">
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo, exercitationem ut? Fuga ad obcaecati ducimus? Voluptate, ipsa tempora ratione odio maxime adipisci praesentium quae pariatur explicabo a, aut natus sed!
          Obcaecati voluptatum dicta laudantium odit id. Consectetur in eos voluptates. Expedita ipsam, necessitatibus voluptas adipisci iusto aspernatur nobis eos alias sunt reprehenderit ad earum rerum suscipit fuga assumenda quae ullam!
          Reprehenderit, voluptatum recusandae cum omnis, non, odit nostrum sed labore modi repudiandae necessitatibus? Facilis molestiae quae quod, accusantium ducimus at dolor corrupti a id voluptatem recusandae voluptatum accusamus consequuntur cupiditate.
          Aliquam, molestias impedit veritatis ut harum tempore totam hic voluptas expedita maxime, omnis libero quia amet dolore id molestiae doloribus alias? Pariatur nisi nemo blanditiis non exercitationem perferendis eos rerum?
          Praesentium repellendus fugit quia beatae necessitatibus quibusdam earum accusamus illo pariatur omnis vero cum, commodi sequi quod aperiam exercitationem debitis quaerat molestiae autem perspiciatis saepe. Debitis vero neque corrupti dignissimos.
          Obcaecati provident aspernatur explicabo sit sint ad cumque, quos veniam fugit beatae ipsa ab commodi. Earum tempore at, facilis ullam, ipsam quos maiores, asperiores sit maxime qui a in expedita?
          Officiis autem nesciunt repudiandae tempore excepturi alias? Adipisci sequi consequatur at aspernatur doloremque autem iusto. Sapiente sit, doloremque quas perferendis ab ratione maiores alias ad et illum corporis earum odio!
          Quo impedit eius vitae quam voluptate dolorum qui ex, repellendus commodi voluptatibus ad minus enim eos omnis, aliquid maiores voluptatum necessitatibus aliquam. Cumque fugit illum hic sequi, ut quos neque?
          Fugit, id repudiandae nulla, aspernatur cum odio velit optio animi alias, obcaecati accusantium magnam tempora eos earum. Voluptatem quae, eum quisquam nisi maxime cumque in eos ex. Laboriosam, nobis optio?
          Explicabo, perspiciatis obcaecati assumenda officia reprehenderit sed molestias, aliquid consectetur veritatis iste facilis asperiores eum ullam modi, deleniti natus consequatur iusto! Voluptatem enim, placeat corrupti aut fugiat ipsa eos nostrum!
          Distinctio asperiores dicta soluta iure sed exercitationem esse dolore sequi harum quidem? Non neque aut doloribus, eos cumque libero ratione laborum odit, deserunt illo laboriosam optio quo impedit ea atque.
          Neque eveniet nihil obcaecati tenetur? Necessitatibus reiciendis asperiores voluptas a ut deserunt incidunt pariatur debitis iure repellat harum dignissimos, consectetur dolorem fuga facere ipsa soluta doloribus eius quae accusantium itaque.
          Facere sunt mollitia sequi optio debitis nihil et soluta totam accusamus veniam reprehenderit, quo sint? Quasi sequi, voluptatum, optio autem quo eum perspiciatis atque obcaecati inventore expedita neque placeat adipisci!
          Autem officia sint doloremque, delectus dignissimos rem quod pariatur cumque molestiae exercitationem odit reiciendis illo velit molestias temporibus, expedita eum labore hic recusandae ipsam! Amet fugit neque dolore et id.
          Officiis harum enim maxime non pariatur iure soluta aut, debitis voluptatibus, magni maiores eos qui dolore tempore adipisci laudantium ipsum eveniet tempora beatae obcaecati odio, amet voluptatem consequuntur. Ab, corporis!
          Quaerat dicta culpa at, sapiente veritatis omnis possimus tempora nostrum ad natus laboriosam consequatur deleniti perspiciatis nobis accusantium, iure rem rerum, dolore officia voluptate iste velit! Eos, quasi velit. Illo.
          Quos quas consequatur consectetur suscipit modi sint inventore nihil itaque laboriosam expedita. Accusantium blanditiis neque similique quos perferendis nisi molestiae quia iure ipsa fuga odit id, reprehenderit officia voluptas non?
          Laudantium sed atque facilis provident aliquid quibusdam incidunt aspernatur? Sunt quis rerum neque quidem labore quos et molestiae deleniti enim vero, beatae, assumenda, excepturi eos iusto sapiente voluptatem temporibus ea?
          Quibusdam nulla est cupiditate nobis sint quia omnis dolore autem dignissimos quasi animi, rem deserunt quaerat excepturi illo vitae quos tempore! Amet nam nesciunt itaque saepe dicta repudiandae illo aperiam!
          Necessitatibus non laboriosam voluptates, at, quisquam quas porro expedita amet voluptas reiciendis officiis ad delectus, obcaecati illo! Velit, sint. Explicabo, nostrum sunt. Repellendus perspiciatis quae ex doloribus eligendi aspernatur ipsa.
          Esse et numquam nostrum expedita illo deleniti sit molestias quia. Consectetur, ab! Molestias quis odio excepturi assumenda vero, nesciunt vel tempora, aperiam atque minima blanditiis voluptate sit aut aliquid rem?
          Nostrum, nihil officia sed, est asperiores animi alias tempora neque quae quos laboriosam fugiat! Impedit sit, reprehenderit necessitatibus voluptatum sint quos labore et officia dolore suscipit, pariatur inventore illum blanditiis.
          Porro impedit quisquam natus est iste libero suscipit autem dolorem, quia blanditiis commodi in tempore tenetur accusantium, iure dolor cumque accusamus odit esse assumenda, illum sit cum ipsum! Ipsa, saepe.
          Sunt cum nesciunt distinctio porro quae et doloribus soluta incidunt non dolore. Reiciendis doloribus iure incidunt dignissimos maxime rerum est, ut, commodi fugiat recusandae esse, atque perferendis. Pariatur, aliquid maxime.
          Dolorem quae blanditiis doloremque laudantium nisi ipsa et ipsam maxime consectetur, sunt excepturi earum velit corporis iste vero ad? Non, velit atque quam harum placeat quidem ipsum laboriosam vel explicabo.
          Quasi neque voluptas, facere consectetur, autem vel nemo iste id aperiam consequatur, saepe ad dolore quam recusandae nam dolor animi? Debitis necessitatibus illo reiciendis mollitia architecto impedit dignissimos eius maiores!
          Itaque veniam eveniet dolorem iusto, ex sapiente nostrum est aut culpa alias voluptatum ratione, facilis dicta quis hic voluptate quod. Itaque labore unde quisquam voluptate nihil ipsa exercitationem quas ratione.
          Facilis nemo nam ex expedita eligendi iste ipsa nihil labore perspiciatis dignissimos, blanditiis quaerat ullam amet iure praesentium nostrum repellat enim quidem fuga vel corporis dolore illo aspernatur? Animi, iste.
          Eos ipsum dolore inventore, eveniet eum recusandae commodi nam vel qui in. Amet est, corporis, aliquam tempore, perspiciatis deserunt delectus quae nemo deleniti quos error commodi. Molestias expedita magnam beatae.
          Aut quo, dicta fuga accusamus perspiciatis laboriosam rerum atque ad eaque architecto, obcaecati consectetur? Laboriosam numquam perferendis, tenetur enim facilis corrupti nisi minus, provident sed labore at aut voluptates illo!
          Atque doloribus ut facere facilis delectus non laboriosam cum voluptatibus praesentium sit architecto, beatae assumenda distinctio est eligendi dolorum consequatur inventore dolore sint, optio maxime numquam, eum aliquid? Saepe, porro.
          Deserunt numquam distinctio et eaque laboriosam sunt possimus quis fugiat aliquid enim quidem pariatur assumenda facilis, dolorum sed? Veritatis repellat laboriosam doloribus fugiat exercitationem animi eos expedita tempora labore velit.
          Dolores excepturi nesciunt nihil nostrum maxime commodi error? Quisquam dolor at eveniet exercitationem. Sunt, repellat atque enim ipsam animi eligendi porro vel dolores voluptatibus amet dolor? Recusandae provident repellendus rem?
          Repellendus inventore dicta odio a dolore amet ad magni rem? Dignissimos optio, laboriosam voluptatem, ullam voluptates molestiae tenetur quos dolores fuga corporis nemo nostrum possimus quis nihil neque facilis hic.
          Incidunt placeat numquam corporis eos ex aspernatur pariatur. Rem corporis, consectetur, ipsum perferendis pariatur tempora, nemo a iste ea et illo deleniti rerum vel mollitia nulla voluptate dicta quae nostrum.
          Beatae cupiditate nam corrupti doloribus doloremque? A commodi dolor suscipit, consequatur repudiandae repellat. Tenetur porro illum, sapiente sed quibusdam, dolorem dolorum voluptas cumque sit ratione tempora facilis voluptatibus hic totam.
          Voluptas explicabo culpa labore minima praesentium nobis odit blanditiis eius, quod quia libero saepe deleniti aut similique! Error quae soluta, cumque odit facilis, sit debitis hic quia, obcaecati esse expedita?
          Assumenda in mollitia aliquam voluptatibus, reprehenderit vitae deleniti odio eaque commodi quisquam exercitationem. Possimus voluptate voluptatem, laudantium et, magni recusandae veritatis ad repellat molestias ducimus provident dignissimos fugit accusantium accusamus?
          Iste aliquid quia asperiores distinctio id magni repudiandae voluptatibus. Harum odio tempora est laboriosam natus amet vero dignissimos eum ipsam quis nemo, aut in maxime, fuga repellendus commodi expedita facere?
          Voluptates deserunt blanditiis, consequuntur, molestias ipsum natus vel ex optio a distinctio cumque. Sed veritatis rem earum? Nihil quam minus animi consequatur neque assumenda ex, eveniet reprehenderit laboriosam, tenetur temporibus.
          Sunt distinctio perspiciatis nemo consectetur possimus earum mollitia voluptatem consequatur reiciendis. Laborum consequatur ipsam nisi veniam a eos nulla at ipsum explicabo aut quod dolores eveniet aspernatur quaerat, deserunt nemo.
          Quo quisquam recusandae ducimus earum exercitationem dolorum vitae eligendi odio suscipit fugit, quibusdam dolorem aliquam adipisci dicta magni inventore nostrum unde iste sequi. Laboriosam, placeat minus debitis quam est perspiciatis.
          Eos consequuntur veritatis asperiores ipsa corporis vitae laborum quo mollitia voluptatibus, voluptatum sed ab quae maxime unde expedita accusantium doloribus debitis, culpa magni reprehenderit, itaque amet nihil exercitationem. Nemo, consectetur!
          Deleniti, cumque nihil! Expedita deleniti temporibus dicta tenetur laborum fugiat, deserunt itaque vero. At, vel voluptates animi dolorum aperiam corrupti dolores neque quae voluptatum perspiciatis quibusdam accusantium asperiores minus quas!
          Aliquid nulla ipsum quaerat expedita tenetur iusto in consequuntur ipsam repellat ad, aut accusamus deserunt id fugiat consequatur doloribus dolorem molestiae soluta vitae rem eveniet inventore, molestias error unde. Quaerat.
          Ullam numquam eos expedita itaque mollitia, velit possimus, voluptatem labore ad reiciendis officiis quisquam commodi non soluta esse minima fuga aliquam modi? Harum, nulla. In excepturi ut dignissimos laborum beatae.
          Alias, culpa! Dolorem, molestias laborum a aliquid fuga illo, quis hic sed debitis ex laboriosam aspernatur obcaecati labore corporis. Tempora amet qui repellat tenetur. Quae ducimus assumenda dicta. Possimus, doloremque.
nda dolor id recusandae qui earum veritatis nostrum mollitia alias suscipit? Consequuntur eos aliquam recusandae. Repudiandae nisi ducimus aliquid, magnam minus dolor beatae incidunt nobis officiis.
          Expedita iste perferendis quas provident tempore saepe dolor voluptatem aliquid minima sunt quo, eum nemo fugiat deserunt voluptatibus sint, dolorum alias! Perferendis blanditiis quis, nisi officia iste et beatae quasi.
          Hic harum voluptatem vitae aut dignissimos exercitationem ad. Nisi, suscipit. Inventore suscipit nemo reprehenderit, dicta vitae in veniam, aperiam ea iste nesciunt placeat doloremque, perferendis accusantium totam praesentium soluta a.
          Labore vitae fugit, qui placeat exercitationem corrupti minima in voluptas doloremque officia voluptate dolore nostrum debitis sapiente. Quasi inventore nam voluptatem officiis possimus repudiandae dolore tempore, accusamus tenetur, nihil architecto?
          Numquam atque, eius fugit quibusdam officia, iusto a et provident odio nesciunt tenetur aut, voluptatibus obcaecati deleniti laboriosam. Deleniti aliquid totam eum quos quasi temporibus corrupti atque iure molestias similique.
          Inventore maiores suscipit id, consequuntur quis provident rerum commodi officiis distinctio soluta, magnam non ducimus. Eius debitis numquam officiis, qui, recusandae asperiores ab assumenda consectetur dolore rem praesentium dolor magnam.
          Inventore numquam nisi ea maxime tenetur, natus ducimus excepturi rerum explicabo qui voluptatum similique. Dolor voluptate minima in minus assumenda quae rem eius, cum, fuga vero architecto nobis vitae dicta?
          Ab molestiae nesciunt ea debitis! Provident, sit nesciunt odit esse beatae ex molestiae, mollitia placeat voluptas dolor debitis error maiores repellendus laborum qui quia vitae fugiat voluptatum! Nisi, dignissimos quam.
          Eos dolor aliquam voluptatum omnis corrupti rem officia distinctio soluta fugit nobis eaque aliquid excepturi exercitationem, ducimus, quia maiores voluptates blanditiis? Omnis, consequatur. Repudiandae in nisi optio quam, qui laborum!
          Sint quas laborum magni molestiae at consequatur sapiente laboriosam iure? Neque labore voluptate distinctio sit doloremque! Quasi, exercitationem optio. Atque aspernatur eveniet similique. Voluptatum laboriosam ab nostrum delectus voluptas quis.
          Temporibus minus reiciendis eligendi adipisci, consequuntur ipsam optio? Sequi porro temporibus commodi rerum laudantium. Commodi porro vel, illo beatae sed voluptatem debitis odio saepe, minima qui et asperiores repellat. Perferendis!
        </textarea>

        <!-- 체크박스 -->
        <div class="form-check mt-2">
          <input
            class="form-check-input"
            type="checkbox"
            value="2"
            id="chk_member2"
          />
          <!-- 구분하기위해 id값 지정 아래 for값도 동일하게 부여-->
          <!-- 동의하시겠습니까? 라는 텍스트를 클릭했을때도 체크가 되게하기위해서다. -->
          <label class="form-check-label" for="chk_member2">
            위 개인정보 취급방침에 동의하시겠습니까?
          </label>
        </div>
        <div class="mt-4 d-flex justfy-content-center gap-2">
          <button class="btn btn-primary w-50" id="btn_member">회원가입</button>
          <button class="btn btn-secondary w-50" id="btn_member_del">가입취소</button>
        </div>



        <!-- display:none 안보이게 숨긴다 -->
        <!-- 이부분은 이동할 페이지인 member_input.php에서 접속자가 회원가입 
             약관절차를 거치고들어왔는지 검사하기위함이다.  -->

        <form method="post" name="stipulation_form" action="member/member_input.php" style="display:none">
          <input type="hidden" name="chk" value="0">
        </form>

      </main>










      

      
