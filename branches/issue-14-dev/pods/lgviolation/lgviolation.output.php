<h1><?= $doc->permalink(); ?></h1>

<?= $doc->body; ?>

<h2>Other Law.Gov Principles:</h2>
<ul>
<? foreach ($POD->getLawGovViolations() as $violation) {
           if ($violation->id != $doc->id) {
                   $violation->output('lgviolation.listitem',dirname(__FILE__));
           }
   }
?>
</ul>
