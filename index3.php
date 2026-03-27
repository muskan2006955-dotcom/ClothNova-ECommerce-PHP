<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smooth Staggered Animation</title>
<style>
body {
  margin:0;
  font-family: Arial, sans-serif;
  background: linear-gradient(to right, #ffe6f0, #fff0e6);
  overflow-x:hidden;
}

.gallery {
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  position:relative;
  overflow:hidden;
}

/* Headings */
h1.main-heading {
  position:absolute;
  top:30px;
  left:50%;
  transform:translateX(-50%) translateY(-20px);
  font-size:2rem;
  font-weight:bold;
  opacity:0;
  transition: opacity 1s ease, transform 1s ease;
}
h5.left-heading, h5.right-heading {
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  opacity:0;
  transition: opacity 1s ease, transform 1s ease;
  font-size:1.1rem;
  font-weight:600;
}
h5.left-heading { left:20px; transform:translate(-40px, -50%); }
h5.right-heading { right:20px; transform:translate(40px, -50%); }

/* Container */
.gallery-container {
  position: relative;
  display: flex;
  align-items: center;
  height: 350px;
}

/* Pic styles */
.pic-wrapper {
  position: absolute;
  width: 250px;
  height: 350px;
  transition: all 1s ease;
}
.pic-wrapper img {
  width:100%;
  height:100%;
  object-fit: cover;
  border-radius:12px;
  box-shadow:0 8px 15px rgba(0,0,0,0.3);
  transition: transform 0.5s ease;
}
.pic-wrapper img:hover {
  transform: scale(1.05);
}

/* Initial overlapping */
.pic-wrapper:nth-child(1){ top:0; left:0; z-index:6; }
.pic-wrapper:nth-child(2){ top:30px; left:0; z-index:5; }
.pic-wrapper:nth-child(3){ top:-30px; left:0; z-index:4; }
.pic-wrapper:nth-child(4){ top:30px; left:0; z-index:3; }
.pic-wrapper:nth-child(5){ top:-30px; left:0; z-index:2; }
.pic-wrapper:nth-child(6){ top:30px; left:0; z-index:1; }

/* When opened horizontally */
.open .pic-wrapper {
  position: relative;
  top:0 !important;
  left:0;
  margin:0 15px;
  transform: translateY(0);
}

/* Staggered smooth delays */
.open .pic-wrapper:nth-child(1){ transition-delay:0.2s; }
.open .pic-wrapper:nth-child(2){ transition-delay:0.6s; }
.open .pic-wrapper:nth-child(3){ transition-delay:1s; }
.open .pic-wrapper:nth-child(4){ transition-delay:1.4s; }
.open .pic-wrapper:nth-child(5){ transition-delay:1.8s; }
.open .pic-wrapper:nth-child(6){ transition-delay:2.2s; }

/* Animate headings after pics */
.open h1.main-heading {
  opacity:1;
  transform:translateX(-50%) translateY(0);
  transition-delay:2.6s;
}
.open h5.left-heading {
  opacity:1;
  transform:translate(0, -50%);
  transition-delay:3s;
}
.open h5.right-heading {
  opacity:1;
  transform:translate(0, -50%);
  transition-delay:3.4s;
}
</style>
</head>
<body>
Lorem ipsum dolor sit amet consectetur, adipisicing elit. Accusamus aliquid voluptate eveniet delectus, praesentium, ex eligendi fuga quia a, quas repellat quasi adipisci! Cumque neque nobis recusandae id, hic eum omnis eos explicabo impedit molestias voluptatibus, expedita dolore, cupiditate corporis maxime ipsam? Assumenda, distinctio sequi veniam consequuntur fuga, perferendis harum temporibus a incidunt minima ex commodi at, impedit aperiam totam velit rerum cum animi modi dolorum minus iusto repellat pariatur. Eligendi ut earum veritatis enim, ipsum deleniti, magnam corporis aliquid vitae illo explicabo exercitationem fugit itaque excepturi voluptatem aspernatur velit commodi quia eius vero quam voluptatibus nisi similique? Ipsa vitae nam cum dolor quas dolorem, facilis beatae obcaecati repudiandae et ea nemo eius excepturi numquam harum officiis rerum eaque totam, sunt quam tempora a sequi. Quo harum maiores iste dolorum atque odit doloribus facilis, quae magni, pariatur nihil quam? Eos perspiciatis, officiis eum nesciunt, porro quis assumenda molestiae, dignissimos mollitia sapiente maiores eaque voluptatum ratione magnam non nemo voluptates cum eveniet perferendis earum dolorum! Impedit quos, architecto consequuntur nulla alias nisi nobis ea pariatur eos deleniti doloremque sequi dolore repellendus distinctio atque! Reiciendis pariatur fugit sint neque voluptas earum a praesentium, velit eaque unde maxime accusamus quod, facilis eius omnis impedit. Dolores facilis ducimus, in eum officiis dolore pariatur quisquam vel suscipit animi? Eius quia tenetur libero suscipit. Eos, aliquid incidunt aut reiciendis laudantium nobis consequatur voluptatem perspiciatis, est distinctio hic excepturi eaque expedita optio ratione voluptas cumque accusantium ullam fugiat exercitationem at laboriosam magni nesciunt? Ut suscipit aut eaque voluptates, voluptatem veniam eos magnam ratione temporibus repellendus nostrum quo dolorem eum beatae est reprehenderit quia soluta possimus incidunt cupiditate consequuntur aliquam ab doloribus ullam? Alias assumenda reprehenderit atque quia beatae quos perferendis quae neque! Illum, ad. Sunt cum quaerat quod culpa similique reprehenderit, eos, repudiandae numquam, excepturi laudantium harum aperiam? Asperiores obcaecati quam amet pariatur ex, perferendis ipsa quidem at debitis temporibus non dolorem minima cupiditate mollitia inventore omnis impedit laboriosam saepe nulla reprehenderit eaque illum modi. Nobis dolor libero adipisci nisi fuga tempora! Harum magnam laborum officiis commodi ipsa mollitia quisquam libero! Nesciunt nulla voluptatibus iure? Officia fuga, neque excepturi distinctio quae nemo! Veritatis, accusamus! Illum, molestiae non ea ullam aspernatur natus soluta deserunt tempore. Perspiciatis quae dolorem consequuntur quisquam ad porro provident doloribus consectetur repellat velit rerum nemo quo ipsum unde, eos tempora placeat laboriosam nam ut mollitia, fugiat inventore? Explicabo consectetur in similique, error, quas sunt commodi debitis perspiciatis illum, eos culpa ipsum dolorum ea ex incidunt fugit nisi nihil unde nam consequuntur animi officiis ipsam porro. Facilis vitae nihil aut ex, dolore libero reiciendis voluptatibus eveniet ullam architecto dicta? Ut molestias hic neque minima soluta distinctio saepe ab, laudantium similique magni odio tempore autem exercitationem cum ducimus impedit ea non vitae porro. Doloremque cumque, distinctio eius temporibus aspernatur dignissimos placeat eum magni sunt reiciendis, deserunt laboriosam est perspiciatis ea dolorum, ducimus pariatur adipisci rem modi voluptas corporis excepturi ipsa quisquam! Dolores sit consectetur quos blanditiis iure nam non, expedita dolorum. Recusandae nam perspiciatis repellendus modi voluptate nulla fugiat voluptates corporis maiores dolore maxime aut nobis vel, molestiae mollitia pariatur inventore enim hic dignissimos corrupti. Deleniti itaque aperiam, dignissimos praesentium cum accusantium modi id et dolore tempore, adipisci ipsam provident sed tenetur exercitationem! Dicta quisquam eaque, similique nulla reiciendis repellat temporibus consequatur ratione debitis? Nulla minus laborum obcaecati quaerat fugiat exercitationem officia, at quo! Modi optio sed sapiente cumque doloremque unde voluptates! Exercitationem, quibusdam officiis voluptatibus facilis cum impedit architecto enim eveniet, vero rem pariatur ipsum? Eaque ullam rem porro nobis consectetur dolor, quo ipsum molestias molestiae animi soluta saepe quasi ab aliquam laudantium quidem ducimus vero minima officia consequuntur beatae odit repudiandae maiores sed. Vitae repellendus, assumenda et voluptates voluptatibus architecto sapiente error hic facere placeat quod autem. Optio accusantium id voluptates veniam? Cupiditate, ex. Repellendus praesentium suscipit doloremque id voluptatum. Dolorem quisquam temporibus itaque molestias, ut error. Optio veritatis, impedit sed illum accusamus nesciunt velit! Delectus ducimus consequuntur laboriosam earum sequi architecto autem cumque esse impedit natus fugit eligendi quasi aliquam, optio labore voluptatem? Vitae, sunt accusantium molestias quo dolores possimus necessitatibus nemo veniam fugiat vero error id impedit nostrum explicabo? Accusantium dolorum maxime, dignissimos commodi error laborum suscipit fugiat nulla delectus, magni, molestiae inventore vero doloremque earum. Suscipit aliquam veniam doloribus ab odit aut alias inventore nemo debitis. Rerum quas consequuntur fuga non officia laudantium corrupti quo ex dolor velit quos, accusantium, vero beatae incidunt dolorum perferendis reprehenderit quia voluptas pariatur, possimus esse architecto maxime in nemo! Repellat sint et exercitationem recusandae minima animi vel nisi officia eligendi pariatur esse iste nihil laboriosam possimus, magnam quas, mollitia a? Magni excepturi tenetur aliquid, saepe dicta odio voluptate eos et, ab libero voluptatibus. Nisi cupiditate, eligendi aliquam asperiores pariatur debitis molestiae. Architecto consectetur commodi, cumque, fugiat rerum exercitationem magni nemo quos impedit necessitatibus sunt libero?
<section class="gallery">
  <h1 class="main-heading">Gallery Showcase</h1>
  <h5 class="left-heading">Left Side Info</h5>
  <h5 class="right-heading">Right Side Info</h5>

  <div class="gallery-container">
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1011/250/350" alt=""></div>
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1015/250/350" alt=""></div>
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1016/250/350" alt=""></div>
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1020/250/350" alt=""></div>
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1024/250/350" alt=""></div>
    <div class="pic-wrapper"><img src="https://picsum.photos/id/1035/250/350" alt=""></div>
  </div>
</section>

<script>
window.addEventListener('scroll', () => {
  const gallery = document.querySelector('.gallery');
  const triggerPoint = gallery.offsetTop - window.innerHeight/2;

  if(window.scrollY > triggerPoint){
    setTimeout(()=>{
      gallery.classList.add('open');
    },2000); // 2 sec delay before starting
  }
});
</script>

</body>
</html>
