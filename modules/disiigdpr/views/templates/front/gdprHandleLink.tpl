<p>
    Le fichier de donnée : {$datafile}
</p>
<p>
    Votre choix :

    {if $status == 0}refusé{/if}
    {if $status == 1}Accepté{/if}
</p>
<p>
    {if $isValid}Votre lien est valide {else} Votre lien n'est pas valide{/if}
</p>