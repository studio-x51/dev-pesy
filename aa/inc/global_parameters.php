<?
// nastaveni global pro vsechny apky!
$CONF_XTRA["TIME_FILES"] = "2016040701";
$CONF_XTRA["smartmailing"]["premium"] = 69;
$CONF_XTRA["smartmailing"]["premium_academy"] = 82;
$CONF_XTRA["smartmailing"]["pdf26napadu"] = 91;
$CONF_XTRA["smartmailing"]["premium_cancel"] = 95;
$CONF_XTRA["smartmailing"]["premium_trial"] = 96;
$CONF_XTRA["smartmailing"]["new_user_zalozka"] = 74;

$CONF_XTRA["SHORT_HOST"] = "sprinte.rs";
$CONF_XTRA["article:author"] = "http://www.socialsprinters.cz/";
$CONF_XTRA["mail_info_sender"] = "noreply@sprinte.rs";
$CONF_XTRA["og:type"] = "product";
$CONF_XTRA["VS"] = date("y")."51300001"; // variabilni symbol pro kazdy novy rok nova rada! 2015 - 1551300001, 2016 - 1651300001, 2017 - 1751300001, ...

$CONF_XTRA["JS_FILES_GLOBAL"] = array("js/5sec-snow.min.js");

/* default og pro pripadny reset/vymazani aplikace */
// (zmenit: title, description, og:title, og:description, like=1, spusteno=0; smazat: canvas, fane_page_id, od, do, timezone)
/* truhla */
//$CONF_XTRA["reset_app"]["2"]["url"] = "https://x51.cz/apps/trezor2/".$_SESSION["aplikace_id"]."/?aplikace_id=".$_SESSION["aplikace_id"]; // musi byt adresa po presmerovani!
// fotosoutez
$CONF_XTRA["reset_app"]["1"]["url"] = "https://x51.cz/apps/ssp-fotosoutez/?aplikace_id="; // to je ok! 
// trezor
$CONF_XTRA["reset_app"]["2"]["url"] = "https://x51.cz/apps/trezor2/"; // musi byt adresa po presmerovani!
// kviz
$CONF_XTRA["reset_app"]["3"]["url"] = "https://x51.cz/apps/ssp-kviz/?aplikace_id="; // 
// zalozka
$CONF_XTRA["reset_app"]["4"]["url"] = "https://x51.cz/apps/ssp-zalozka/?aplikace_id="; // 
// video share
$CONF_XTRA["reset_app"]["5"]["url"] = "https://x51.cz/apps/ssp-video-share/?aplikace_id="; // 
// budovani databaze
$CONF_XTRA["reset_app"]["6"]["url"] = "https://x51.cz/apps/ssp-zisk-zdarma/?aplikace_id="; // 
// kolo stesti
$CONF_XTRA["reset_app"]["7"]["url"] = "https://x51.cz/apps/ssp-kolo-stesti/?aplikace_id="; // 
// kolo stesti
$CONF_XTRA["reset_app"]["8"]["url"] = "https://x51.cz/apps/ssp-instagram/?aplikace_id="; // 

/* kroky aplikaci */
$CONF_XTRA["steps"]["2"] = 7;


/* nastaveni zadavani uzivatelskych udaju, jmeno, adresy, email ... */
$CONF_XTRA["setting-adress"][1] = array("jmeno_prijmeni","email","telefon");
/* nastaveni zadavani uzivatelskych udaju, jmeno, adresy, email ... */
$CONF_XTRA["setting-adress"][2] = array("krestni-jmeno","prijmeni","email","adresa","telefon");
//$CONF_XTRA["setting-adress-mandatory"][2] = array("email"); // povinne a nelze smazat! (povinna polozka u zadavani polozek formulare {email musi byt!!!})
$CONF_XTRA["setting-adress"][7] = array("krestni-jmeno","prijmeni","email","adresa","telefon");
//$CONF_XTRA["setting-adress-mandatory"][7] = array("email"); // povinne a nelze smazat! (povinna polozka u zadavani polozek formulare {email musi byt!!!})
$CONF_XTRA["setting-adress"][6] = array("email"); // pro zadavni adresy, staci zde email
$CONF_XTRA["setting-adress-mandatory"][6] = array("email"); // a email je povinny a nelze smazat!

/* FB og */
$CONF_XTRA["texty"]["cs"]["ss-fb_og_title"] = "Nejjednodušší způsob jak pro svou stránku na Facebooku vytvořit vlastní aplikaci";
$CONF_XTRA["texty"]["cs"]["ss-fb_og_description"] = "Přidejte na svou firemní stránku profesionální soutěž, nebo aplikaci pro sběr kontaktů. Vše zvládnete hravě a sami během několika minut.";

/* gopay formular */
$CONF_XTRA["texty"]["cs"]["dashboard-description_licence-premium"] = "SocialSprinters Premium Members";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_members"] = "SocialSprinters Premium Members";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_academy"] = "Studio x51 Academy PREMIUM";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_trial"] = "Studio x51 Academy PREMIUM Trial";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_pdf26napadu"] = "Video ze Studio x51 academy";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_members-button_vstup"] = "Vstoupit do aplikace";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_members-title-gratulace"] = "Gratulujeme. Platba byla úspěšná";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_members-label-zadejte_email"] = "Zadejte váš email";
$CONF_XTRA["texty"]["cs"]["setting-platba_description-ss_premium_members-label-zkotrolujte_si_email"] = "Zkontrolujte si váš email";
/* /gopay formular */

/* countdown */
$CONF_XTRA["texty"]["cs"]["setting-do_konce_souteze_zbyva"] = "Do konce soutěže zbývá";
$CONF_XTRA["texty"]["cs"]["setting-countdown-dnu"] = "dnů";
$CONF_XTRA["texty"]["cs"]["setting-countdown-hodin"] = "hodin";
$CONF_XTRA["texty"]["cs"]["setting-countdown-minut"] = "minut";
$CONF_XTRA["texty"]["cs"]["setting-countdown-vterin"] = "vteřin";
/* /countdown */

/* obecne texty napri aplikaceni */
$CONF_XTRA["texty"]["cs"]["seting-stan-se-fanouskem-fb"] = "Staň se fanouškem naší stránky";
$CONF_XTRA["texty"]["cs"]["seting-close_pop_win"] = "Zavřít okno";
$CONF_XTRA["texty"]["cs"]["setting-adress_krestni-jmeno"] = "Křestní jméno";
$CONF_XTRA["texty"]["cs"]["setting-adress_prijmeni"] = "Příjmení";
$CONF_XTRA["texty"]["cs"]["setting-adress_jmeno_prijmeni"] = "Celé jméno";
$CONF_XTRA["texty"]["cs"]["setting-adress_email"] = "E-mail";
$CONF_XTRA["texty"]["cs"]["setting-adress_adresa"] = "Korespondenční adresa";
$CONF_XTRA["texty"]["cs"]["setting-adress_telefon"] = "Telefon";
$CONF_XTRA["texty"]["cs"]["setting-adress_title_na_jako_adresu_mame_zaslat_vyhru"] = "Na jakou adresu ti máme tvou výhru doručit?";
$CONF_XTRA["texty"]["cs"]["setting-adress_button_na_jako_adresu_mame_zaslat_vyhru"] = "Odeslat formulář";

//https://x51.cz/apps/ssp-kolo-stesti/?aplikace_id=324&type_zalozka=group

$CONF_XTRA["texty"]["cs"]["SocialSprinters"] = "SocialSprinters";
$CONF_XTRA["texty"]["cs"]["reset_app_1_title"] = "Fotosoutěž";
$CONF_XTRA["texty"]["cs"]["reset_app_1_descr"] = "Nahrej tématické fotky, získejte hlasy od svých přátel a vyhraj některou ze skvělých cen!";
$CONF_XTRA["texty"]["cs"]["reset_app_1_typ"] = "Fotosoutěž";
$CONF_XTRA["texty"]["cs"]["reset_app_2_title"] = "Tipovací soutěž";
$CONF_XTRA["texty"]["cs"]["reset_app_2_descr"] = "Tipni si kód a vyhraj";
$CONF_XTRA["texty"]["cs"]["reset_app_2_typ"] = "Tipni si kód a vyhraj";
$CONF_XTRA["texty"]["cs"]["reset_app_3_title"] = "Tipni správnou odpověď!";
$CONF_XTRA["texty"]["cs"]["reset_app_3_descr"] = "Zkus si náš kvíz a hádej správné odpovědi!";
$CONF_XTRA["texty"]["cs"]["reset_app_3_typ"] = "Tipni správnou odpověď!";
$CONF_XTRA["texty"]["cs"]["reset_app_4_title"] = "Záložka";
$CONF_XTRA["texty"]["cs"]["reset_app_4_descr"] = "Záložka";
$CONF_XTRA["texty"]["cs"]["reset_app_4_typ"] = "Záložka s informacemi";
$CONF_XTRA["texty"]["cs"]["reset_app_5_title"] = "Sdílení videa";
$CONF_XTRA["texty"]["cs"]["reset_app_5_descr"] = "Sdílení videa";
$CONF_XTRA["texty"]["cs"]["reset_app_5_typ"] = "Sdílení videa";
$CONF_XTRA["texty"]["cs"]["reset_app_6_title"] = "Stáhněte si ebook!";
$CONF_XTRA["texty"]["cs"]["reset_app_6_descr"] = "Stáhněte si ebook!";
$CONF_XTRA["texty"]["cs"]["reset_app_6_typ"] = "Budování emailové databáze";
$CONF_XTRA["texty"]["cs"]["reset_app_7_title"] = "Zkus štěstí na kole štěstí!";
$CONF_XTRA["texty"]["cs"]["reset_app_7_descr"] = "Roztoč na naší stránce každý den kolo štěstí a vyhraj některou ze skvělých cen!";
$CONF_XTRA["texty"]["cs"]["reset_app_7_typ"] = "Zkus štěstí na kole štěstí!";
$CONF_XTRA["texty"]["cs"]["reset_app_8_title"] = "Instagram";
$CONF_XTRA["texty"]["cs"]["reset_app_8_descr"] = "Sledujte nás na Instagramu";
$CONF_XTRA["texty"]["cs"]["reset_app_8_typ"] = "Instagram";

$CONF_XTRA["texty"]["cs"]["setting-vstup-placeholder_zadejte_heslo"] = "Zadejte heslo";
$CONF_XTRA["texty"]["cs"]["setting-button_vstup"] = "Odeslat";
$CONF_XTRA["texty"]["cs"]["setting-button_fb_login"] = "Propojit s Facebookem";
$CONF_XTRA["texty"]["cs"]["setting-button_prejit_k_tematu"] = "Přejít k výběru tématu";
$CONF_XTRA["texty"]["cs"]["setting-link_logout"] = "Odhlásit";
$CONF_XTRA["texty"]["cs"]["setting-link_nastaveni"] = "Nastavení";
$CONF_XTRA["texty"]["cs"]["setting-link_nastaveni-lang"] = "Jazyk";
$CONF_XTRA["texty"]["cs"]["setting-link_nastaveni-fakturace"] = "Fakturace";
$CONF_XTRA["texty"]["cs"]["setting-link_prehled-fakturace"] = "Faktury";
$CONF_XTRA["texty"]["cs"]["setting-button_prejit_k_tematu_2"] = "Typni kód - přejít k výběru tématu"; // trezor
$CONF_XTRA["texty"]["cs"]["setting-button_prejit_k_tematu_4"] = "Záložka - přejít k výběru tématu"; // zalozka

$CONF_XTRA["texty"]["cs"]["setting-title_nastaveni_jazyka"] = "Nastavení jazyka";
$CONF_XTRA["texty"]["cs"]["setting-text_nastaveni_jazyka"] = "Zvolte jazyk:";
$CONF_XTRA["texty"]["cs"]["setting-nastaveni_jazyka-cestina"] = "Čeština";
$CONF_XTRA["texty"]["cs"]["setting-nastaveni_jazyka-slovenstina"] = "Slovenčina";
$CONF_XTRA["texty"]["cs"]["setting-nastaveni_jazyka-anglictina"] = "Angličtina";
$CONF_XTRA["texty"]["cs"]["setting-nastaveni_jazyka-nemcina"] = "Němčina";

$CONF_XTRA["texty"]["cs"]["setting-title_nastaveni_fakturace"] = "Nastavení fakturačních údajů";
$CONF_XTRA["texty"]["cs"]["setting-title_prehled_fakturace"] = "Přehled vystavených faktur";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_stav"] = "Stav";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_vs"] = "Variabilní s.";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_prijemce"] = "Jméno příjemce";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_datum_vystaveni"] = "Datum vystavení";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_datum_splatnosti"] = "Datum splatnosti";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_typ_dokladu"] = "Typ dokladu";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-title_castka"] = "Částka";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-stav-zaplaceno"] = "Zaplacená";
$CONF_XTRA["texty"]["cs"]["setting-table_prehled_fakturace-typ_dokladu-danovy"] = "Daňový doklad";

$CONF_XTRA["texty"]["cs"]["setting-kontaktni_email"] = "Kontaktní e-mail";
$CONF_XTRA["texty"]["cs"]["setting-kontaktni_email-info_text"] = "Potvrďte prosím váš e-mail, na který vám v budoucnu budeme moci doručit fakturu, návody a také dárky v podobě volných kreditů.<br /> Žádný spam od nás nečekejte.";

$CONF_XTRA["texty"]["cs"]["menu-blog"] = "Blog";
$CONF_XTRA["texty"]["cs"]["menu-cases"] = "Případové studie";
$CONF_XTRA["texty"]["cs"]["menu-cenik"] = "Ceník";
$CONF_XTRA["texty"]["cs"]["menu-kontakt"] = "Kontakt";
$CONF_XTRA["texty"]["cs"]["menu-dashboard"] = "Zpět na přehled aplikací";
$CONF_XTRA["texty"]["cs"]["setting-posledni_krok-link-dashboard"] = "Přehled aplikací";
$CONF_XTRA["texty"]["cs"]["setting-adress_upravte_pole"] = "Upravte pole v kontaktním formuláři";
$CONF_XTRA["texty"]["cs"]["setting-adress_vyzadovat"] = "Vyžadovat";
$CONF_XTRA["texty"]["cs"]["setting-adress_add_field"] = "+ přidat další pole";
$CONF_XTRA["texty"]["cs"]["link-ziskat-vyhodneji_ss-premium"] = "Získejte ji výhodněji jako člen SocialSprinters Premium";

$CONF_XTRA["texty"]["cs"]["setting-placeholder_zadejte_og_title"] = "Zadejte titulek";
$CONF_XTRA["texty"]["cs"]["setting-placeholder_zadejte_og_description"] = "Zadejte popis";
$CONF_XTRA["texty"]["cs"]["setting-tip_zadejte_og"] = "Můžete přidat vlastní náhledový obrázek, nebo upravit text, který se zobrazí při sdílení soutěže.";
$CONF_XTRA["texty"]["cs"]["setting-tip_zadejte_og_dashboard"] = "Nahrajte vlastní náhledový obrázek a text, který se zobrazí při každém sdílení soutěže. Pokud budete aplikaci sponzorovat, obrázek nesmí obsahovat více než 20% textu, to si můžete jednoduše ověřit <a href='https://www.facebook.com/ads/tools/text_overlay?_rdr' target='_blank'>zde</a>.";
$CONF_XTRA["texty"]["cs"]["setting-tip_zadejte_og_dashboard_obrazek"] = "Obrázek vložte ve formátu *.PNG a rozlišení 1200px x 628px.";
$CONF_XTRA["texty"]["cs"]["setting-tip_zadejte_og_dashboard_obrazek_noshow"] = "Změnili jste obrázek, ale i přesto se na FB nezobrazuje? Návod <a href='https://www.facebook.com/ads/tools/text_overlay?_rdr' target='_blank'>zde</a>.";
$CONF_XTRA["texty"]["cs"]["setting-pravidla_title"] = "Doplňte chybějící žlutě vyznačená pole v pravidlech vaší soutěže";
$CONF_XTRA["texty"]["cs"]["setting-pravidla_aplikace_title"] = "Doplňte chybějící žlutě vyznačená pole v pravidlech vaší aplikace";
$CONF_XTRA["texty"]["cs"]["setting-pravidla_resetovat"] = "Resetovat";
$CONF_XTRA["texty"]["cs"]["setting-confirm_pravidla_resetovat"] = "Opravdu smazat tato pravidla a načíst výchozí?";
$CONF_XTRA["texty"]["cs"]["setting-confirm_delete_price"] = "Opravdu smazat cenu?";
$CONF_XTRA["texty"]["cs"]["setting-confirm_change_price"] = "Upozornění: Změnou výher v již spuštěné soutěži přijdete o dosavadní seznam výherců. Chcete i přes to pokračovat?";


/* add tab */
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_other_FB_page"] = "Tato aplikace není nastavena k této stránce";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_aplikace_added_to_FB_title"] = "Aplikace byla přidána na Facebook";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_aplikace_added_to_FB_text"] = "Zatím ale není dostupná veřejnosti. Dokončete její přidání.";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_aplikace_ready"] = "Vaše aplikace je připravena";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_button_add"] = "Přidej aplikaci na vaši Facebook stránku";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_login_and_setFB"] = "Vyberte Facebook stránku na kterou ji chcete umístit";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_gotopay"] = "Přejít k platbě";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_gotodashboard"] = "Přehled vašich aplikací";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_login_and_setFB_new"] = "Změnit Facebook stránku umístění aplikace";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_showFB"] = "Zobrazit aplikaci na Facebooku";
$CONF_XTRA["texty"]["cs"]["setting-add2FBTab_public_info"] = "Aplikace nebude dostupná pro veřejnost";
/* /add tab */

/* feedback */
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_yes"] = "Perfektní";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_neutral"] = "Dobrý, ale ...";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_smiley_no"] = "Špatný";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_title"] = "Jak byste ohodnotili proces <br />nastavení vaší aplikace?";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_send"] = "Odeslat";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_title_message"] = "Napište nám prosím, co můžeme zlepšit";
$CONF_XTRA["texty"]["cs"]["setting-add2FBapp_feedback_thanks"] = "Děkujeme! :-)";
/* / feedback */

$CONF_XTRA["texty"]["cs"]["setting-neni_fb_aplikace_k_prirazeni"] = "BOHUŽEL SE NEPODAŘILO VYTVOŘIT APLIKACI";
$CONF_XTRA["texty"]["cs"]["setting-next_step"] = "Pokračovat";
$CONF_XTRA["texty"]["cs"]["setting-prev_step"] = "Zpět";
$CONF_XTRA["texty"]["cs"]["setting-button_ulozit"] = "Uložit";
$CONF_XTRA["texty"]["cs"]["setting-button_confirm"] = "Potvrdit";
$CONF_XTRA["texty"]["cs"]["setting-are_you_sure_delete_app"] = "Opravdu smazat tuto aplikaci bez možnosti navrácení?";
$CONF_XTRA["texty"]["cs"]["setting-help_button_next"] = "Až bude vše připraveno,<br /> pokračuj na další stránku";
$CONF_XTRA["texty"]["cs"]["setting-help_button_next"] = "Až bude vše připraveno,<br /> pokračuj na další stránku";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-default"] = "Upravte vzhled Vaší aplikace kliknutím na libovolný prvek. Po kliknutí na prvek se otevře nabídka alternativních prvků. Zvolte si takový, který bude nejlépe vyhovovat vaší představě. Až budete s kompletním vzhledem spokojeni, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-prizes_setting"] = "Přidejte či upravte ceny v soutěži. Až budete mít ceny zadány, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-baners_fb_setting"] = "Zde můžete zadat reklamní banery a nastavit podobu sdílení na Facebooku. Až budete mít hotovo, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-adress_setting"] = "Zde si můžete upravit kontaktní formulář, který vyplňují výherci. Můžete přidat nebo ubrat kolonky, které vás zajímají a zvolit zda je tato povinná. Až budete mít hotovo, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-rules_setting"] = "Upravte si pravidla soutěže. Až budete mít hotovo, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-pagetab_setting"] = "Přihlašte se do svojí nové aplikace a přidejte ji na svůj Facebook. Až budete mít hotovo, můžete se podívat na výsledek";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-skin-text-photo_setting"] = "Upravte vzhled Vaší aplikace kliknutím na libovolný prvek. Po kliknutí na prvek se otevře nabídka alternativních prvků, případně po kliknutí na ikonu foťáku můžete nahrát svůj obrázek, který bude automaticky oříznut na patřičnou velikost. Až budete mít hotovo, pokračujte na další stranu v horní části stránky.";
$CONF_XTRA["texty"]["cs"]["setting-help_hlavni_text-pay"] = "Platební stránka, postupujte dle ...";
$CONF_XTRA["texty"]["cs"]["setting-help_slick_text"] = "Zvolte si kliknutím takový prvek, který bude nejlépe vyhovovat vaší představě.";
$CONF_XTRA["texty"]["cs"]["setting-help_set_tema_text"] = "Vyberte si téma vaší aplikace.";
$CONF_XTRA["texty"]["cs"]["setting-help_set_skin_text"] = "Vyberte si skin vaší aplikace.";
$CONF_XTRA["texty"]["cs"]["setting-nezobrazovat_hlavni-help"] = "Už to znám! Nezobrazovat help k této aplikaci.";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_barvu_pozadi"] = "Vyberte barvu pozadí"; 

$CONF_XTRA["texty"]["cs"]["setting-platba_okno-title"] = "Proveďte platbu vaší aplikace";
$CONF_XTRA["texty"]["cs"]["setting-platba_vyber-delky-trvani-title"] = "Vyberte délku trvání chodu aplikace";
$CONF_XTRA["texty"]["cs"]["setting-platba_vyber-delky-trvani-1m"] = "1 měsíc";
$CONF_XTRA["texty"]["cs"]["setting-platba_vyber-delky-trvani-3m"] = "3 měsíce";
$CONF_XTRA["texty"]["cs"]["setting-platba_vyber-delky-trvani-6m"] = "6 měsíců";
$CONF_XTRA["texty"]["cs"]["setting-platba_vyber-delky-trvani-12m"] = "12 měsíců";
$CONF_XTRA["texty"]["cs"]["setting-platba_zadej-slevovy-kupon"] = "Zadejte slevový kupón"; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_zadej-slevovy-kupon_ok"] = "OK"; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_sleva"] = "Sleva "; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_uplatnen"] = "Uplatněn slevový kód"; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_BAD"] = "Neplatný kód."; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_TOO-SOON"] = "Platnost tohoto kódu ještě nenastala."; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_EXPIRED"] = "Platnost tohoto kódu již vypršela."; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_USED_BY_OWN"] = "Tento kód jste již uplatnil."; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_slevovy-kupon_USED_BY_OTHER"] = "Tento kód byl již uplatněn."; // 
$CONF_XTRA["texty"]["cs"]["setting-platba_nastavena"] = "OK"; // po odeslani nastavi platebni branu!
$CONF_XTRA["texty"]["cs"]["setting-platba_provest_platbu"] = "Provést platbu"; // button vyvolat platebni branu
$CONF_XTRA["texty"]["cs"]["setting-platba_login-provest_platbu"] = "Přihlásit na Facebook a <br>provést platbu"; // button vyvolat platebni branu
$CONF_XTRA["texty"]["cs"]["setting-platba_cena_celkem"] = "Cena celkem:";
$CONF_XTRA["texty"]["cs"]["setting-platba_cena_sleva"] = "Sleva za platbu celé částky najednou:";
$CONF_XTRA["texty"]["cs"]["setting-platba_cena_sleva-slev_kupon"] = "Sleva za slevový kupón:";
$CONF_XTRA["texty"]["cs"]["setting-platba_set-pay_month"] = "Měsíční opakovaná platba";
$CONF_XTRA["texty"]["cs"]["setting-platba_set-pay_all"] = "Celá částka najednou";
$CONF_XTRA["texty"]["cs"]["setting-platba_set-pay_monthly_reccurency"] = "Platba měsíčně:";
$CONF_XTRA["texty"]["cs"]["setting-platba_set-pay_month_all-title"] = "Preferovaná platba";
$CONF_XTRA["texty"]["cs"]["link-pravidla_souteze"] = "Pravidla soutěže";
$CONF_XTRA["texty"]["cs"]["link-pravidla_aplikace"] = "Pravidla aplikace";

/* nabidka vsech aplikaci */
$CONF_XTRA["texty"]["cs"]["all-app_config_99_name"] = "Obecné";
$CONF_XTRA["texty"]["cs"]["all-app_title"] = "Objevte a přidejte na svou stránku nové aplikace";
$CONF_XTRA["texty"]["cs"]["all-app_title-zadne_aplikace"] = "Vyberte si nejlepší aplikaci pro vaši stránku na Facebooku";
$CONF_XTRA["texty"]["cs"]["all-app_show-other-app"] = "<span>+</span>Zobrazit další aplikace";
$CONF_XTRA["texty"]["cs"]["all-app_hide-other-app"] = "<span>-</span>Schovat nabídku aplikací";

/* zalozka 4 */
$CONF_XTRA["all-app_config"][2]["aplikace_typ_id"] = 4; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_2_name"] = "Záložka s informacemi";
$CONF_XTRA["texty"]["cs"]["all-app_config_2_info"] = "Potřebujete rychle informovat vaše fanoušky? Přidejte na svou stránku informační záložku.";

/* truhla 2 */
$CONF_XTRA["all-app_config"][1]["aplikace_typ_id"] = 2; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_1_name"] = "Tipování kódů";
$CONF_XTRA["texty"]["cs"]["all-app_config_1_info"] = "Soutěže, ve kterých lidé hádají výherní kombinace na displeji telefonu, trezoru nebo truhly.";
/*  kolo 7 */
//$CONF_XTRA["all-app_config"][7]["aplikace_typ_id"] = 0; // atribut rel
//if($_SERVER["REMOTE_ADDR"] == "87.249.153.140") // rybna
//if($_SERVER["REMOTE_ADDR"] == "84.42.152.67")
$CONF_XTRA["all-app_config"][4]["aplikace_typ_id"] = 7; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_4_name"] = "Kola štěstí";
$CONF_XTRA["texty"]["cs"]["all-app_config_4_info"] = "Soutěžící roztáčejí kolo štěstí a snaží se trefit pole na kterém je umístěna výhra.";
/* fotosoutez 1 */
$CONF_XTRA["all-app_config"][7]["aplikace_typ_id"] = 1; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_7_name"] = "Fotosoutěže";
$CONF_XTRA["texty"]["cs"]["all-app_config_7_info"] = "Soutěžící nahrávají tématické fotky, pro které následně získavají hlasy od svých přátel.";
/* budovani databaze 6 */
$CONF_XTRA["all-app_config"][3]["aplikace_typ_id"] = 6; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_3_name"] = "Budování databáze";
$CONF_XTRA["texty"]["cs"]["all-app_config_3_info"] = "Získejte e-mailové adresy fanoušků do vaší e-mailové databáze pro další komunikaci.";
/*  */
//$CONF_XTRA["all-app_config"][8]["aplikace_typ_id"] = 0; // atribut rel
//if($_SERVER["REMOTE_ADDR"] == "84.42.152.67")
//if($_SESSION["x51admin"])
$CONF_XTRA["all-app_config"][8]["aplikace_typ_id"] = 3; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_8_name"] = "Vědomostní testy";
$CONF_XTRA["texty"]["cs"]["all-app_config_8_info"] = "Otestujte své fanoušky nebo od nich získejte zpětnou vazbu pomocí kvízů a testů.";

/* Instagram */
$CONF_XTRA["all-app_config"][10]["aplikace_typ_id"] = 8; // atribut rel
//if($_SERVER["REMOTE_ADDR"] == "84.42.152.67")
//if($_SESSION["x51admin"])
//	$CONF_XTRA["all-app_config"][10]["aplikace_typ_id"] = 8; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_10_name"] = "Instagram";
$CONF_XTRA["texty"]["cs"]["all-app_config_10_info"] = "Propojte si vaši Facebookovou stránku s Instagram účtem, nebo specifickým #hashtagem, pod kterým lidé sdílejí fotografie o vás.";



/*  */
$CONF_XTRA["all-app_config"][5]["aplikace_typ_id"] = 0; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_5_name"] = "Prodej";
$CONF_XTRA["texty"]["cs"]["all-app_config_5_info"] = "Získejte nové zákazniky prodejem produktů a služeb přímo na vaši Facebook stránce.";
/*  */
$CONF_XTRA["all-app_config"][6]["aplikace_typ_id"] = 0; // atribut rel
if($_SERVER["REMOTE_ADDR"] == "84.42.152.67")
	$CONF_XTRA["all-app_config"][6]["aplikace_typ_id"] = 5; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_6_name"] = "Placení sdílením";
$CONF_XTRA["texty"]["cs"]["all-app_config_6_info"] = "Nabídněte fanouškům zajímavý obsah zdarma, výměnou za sdílení na jejich osobním profilu.";

/*  */
$CONF_XTRA["all-app_config"][9]["aplikace_typ_id"] = 0; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_9_name"] = "Úkolové soutěže";
$CONF_XTRA["texty"]["cs"]["all-app_config_9_info"] = "Nechte soutěžící hledat a plnit speciální úkoly, které pomohou rozšířit povědomí o vaší značce.";

/* Omezená nabídka  */
$CONF_XTRA["all-app_config"][11]["aplikace_typ_id"] = 0; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_11_name"] = "Omezená nabídka";
$CONF_XTRA["texty"]["cs"]["all-app_config_11_info"] = "Tato aplikace vám umožní udělat časově omezenou nabídku. Můžete pro fanoušky zvýhodnit konkrétní produkt, nebo dát množstevní slevu.";
/* Formulář */
$CONF_XTRA["all-app_config"][12]["aplikace_typ_id"] = 0; // atribut rel
$CONF_XTRA["texty"]["cs"]["all-app_config_12_name"] = "Formulář";
$CONF_XTRA["texty"]["cs"]["all-app_config_12_info"] = "Potřebujete aby vám o sobě lidé zanechali informace, registrovali se, nebo prostě jen napsali svůj názor? Pak vám aplikace Formulář přijde vhod.";
/* /nabidka vsech aplikaci */

/* webinar academy + upis do premium academy */
$CONF_XTRA["texty"]["cs"]["setting-academy_upis-platba_login-provest_platbu"] = "Přihlásit na Facebook a <br>provést platbu"; // button vyvolat platebni branu
$CONF_XTRA["texty"]["cs"]["form_check-err_musite_souhlas_s_obch_podminkami"] = "Musíte souhlasit s obchodními podmínkami";
/* /webinar academy + upis do premium academy */



$CONF_XTRA["texty"]["cs"]["setting-pop_okno-gratulace_aplikace_done"] = "Vaše aplikace je hotová!";
$CONF_XTRA["texty"]["cs"]["setting-pop_okno-gratulace_aplikace_text_share"] = "Pokud jste připraveni, dejte o ní vědět světu";
$CONF_XTRA["texty"]["cs"]["dashboard_title"] = "Přehled vašich aplikací";
$CONF_XTRA["texty"]["cs"]["dashboard_title-button-uloz"] = "Ulož";
$CONF_XTRA["texty"]["cs"]["dashboard_title-clik-edit"] = "Kliknutím změnte název aplikace ";
$CONF_XTRA["texty"]["cs"]["dashboard_pridej-app-na-FB"] = "Přidejte aplikaci na svou stránku";
$CONF_XTRA["texty"]["cs"]["dashboard-description_termin"] = "Termín:";
$CONF_XTRA["texty"]["cs"]["dashboard-description_termin-od"] = "Od";
$CONF_XTRA["texty"]["cs"]["dashboard-description_termin-do"] = "do";
$CONF_XTRA["texty"]["cs"]["dashboard-description_licence"] = "Licence:";
$CONF_XTRA["texty"]["cs"]["dashboard-description_licence-free"] = "FREE";
$CONF_XTRA["texty"]["cs"]["dashboard-description_licence-placena"] = "Placená";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav"] = "Stav:";
$CONF_XTRA["texty"]["cs"]["dashboard-description_swich-title-spusteno"] = "Skrýt aplikaci na stránce";
$CONF_XTRA["texty"]["cs"]["dashboard-description_swich-title-stopnuto"] = "Zobrazit aplikaci na stránce";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-spusteno"] = "Spuštěno";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-zaplaceno_spusteno"] = "Spuštěno";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-zaplaceno"] = "Zaplaceno";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-zaplaceno_stopnuto"] = "Zaplaceno, stopnuto";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-nezaplaceno"] = "Nezaplaceno";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-stopnuto"] = "Stopnuto";
$CONF_XTRA["texty"]["cs"]["dashboard-description_stav-ukonceno"] = "Ukončeno";
$CONF_XTRA["texty"]["cs"]["dashboard-description_termin-neomezeno"] = "Neomezen";
$CONF_XTRA["texty"]["cs"]["dashboard-link_short_share"] = "Odkaz pro sdílení aplikace:";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-uzivatelu"] = "Počet uživatelů aplikace";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-ziskanych-emailu"] = "Počet získaných e-mailů";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-zobrazeni"] = "Počet zobrazení aplikace";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_konverzni-pomer"] = "Konverzní<br /> poměr";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-vyhranych-cen"] = "Počet vyhraných cen";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-tipu-na-trezor-klavesnici"] = "Počet tipů na klávesnici";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_pocet-pokusu-otoceni-kolem-stesti"] = "Počet pokusů<br> v soutěži";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_seznam-vyhercu-souteze"] = "Seznam výherců soutěže";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_datum_zalozeni"] = "Datum vložení";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_id_vyhry"] = "Číslo výhry";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_nazev_vyhry"] = "Název výhry";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_poradi_v_soutezi"] = "Pořadí";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_score_v_soutezi"] = "Počet bodů";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_fotka_id"] = "Id fotografie";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_fotka_max_cas_hlas"] = "Poslední hlas";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_fotka_popis"] = "Popis fotografie";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_celkovy_pocet_fotografii"] = "Počet fotografií v soutěži";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_celkovy_pocet_hlasu"] = "Celkový počet hlasů";
$CONF_XTRA["texty"]["cs"]["dashboard-link_to-editor"] = "Přejít do editoru a upravit aplikaci";
$CONF_XTRA["texty"]["cs"]["dashboard-link_exportovat-vyherce"] = "Seznam výherců soutěže";
$CONF_XTRA["texty"]["cs"]["dashboard-link_exportovat-data"] = "Exportovat data";
$CONF_XTRA["texty"]["cs"]["dashboard-link_download-all-photos-as-zip"] = "Stáhnout fotografie";
$CONF_XTRA["texty"]["cs"]["dashboard-description_konci-za"] = "končí za";
$CONF_XTRA["texty"]["cs"]["dashboard-description_skoncila"] = "skončila";
$CONF_XTRA["texty"]["cs"]["dashboard-description_platba-link"] = "Zaplatit";
$CONF_XTRA["texty"]["cs"]["dashboard-description_platba-link-prodlouzit"] = "Prodloužit";
$CONF_XTRA["texty"]["cs"]["sklonuj-den_1"] = "den";
$CONF_XTRA["texty"]["cs"]["sklonuj-den_2-4"] = "dny";
$CONF_XTRA["texty"]["cs"]["sklonuj-den_>=5"] = "dnů";
$CONF_XTRA["texty"]["cs"]["sklonuj-otazka_1"] = "otázka";
$CONF_XTRA["texty"]["cs"]["sklonuj-otazka_2-4"] = "otázky";
$CONF_XTRA["texty"]["cs"]["sklonuj-otazka_>=5"] = "otázek";
$CONF_XTRA["dateformat"]["cs"] = "d.m.Y";
$CONF_XTRA["texty"]["cs"]["tab-admin-aplikace_vstup-administrace_link-na-dashboard"] = "Vstup do administrace SocialSprinters";
$CONF_XTRA["texty"]["cs"]["STOP-aplikace_aplikace-je-pozastavena"] = "Aplikace je pozastavena";
$CONF_XTRA["texty"]["cs"]["STOP-aplikace_aplikace-neni-prirazena-zadne-FB-strance"] = "Aplikace není přiřazena žádné FB stránce";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-TEMA_title"] = "Skvělá volba!";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-TEMA_text"] = "Nyní se můžete pustit do editace a přípravy vaší nové aplikace.<br /> U této aplikace máte na výběr z několika různých témat a skinů,<br /> ze kterých můžete vycházet a které vám práci zjednoduší.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-SKIN_text"] = "Nyní se můžete pustit do editace a přípravy vaší nové aplikace.<br /> U této aplikace máte na výběr z několika různých skinů,<br /> ze kterých můžete vycházet a které vám práci zjednoduší.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-TEMA_subtitle"] = "Začněte výběrem tématu.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-SKIN_subtitle"] = "Začněte výběrem skinu.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-TEMA_button"] = "Ok, jdu na to.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-UPRAV-SKIN_title"] = "Přizpůsobení vzhledu vaší aplikace";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-UPRAV-SKIN_text"] = "Vámi vybraný skin můžete libovolně přizpůsobit.<br /> Pro změnu libovolného prvku stačí kliknout a vybrat jiný.<br />Až budete se vzhledem spokojeni, přejděte na další stránku<br /> kliknutím na tlačítko &quot;Pokračovat&quot;";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-UPRAV-SKIN_button"] = "Ok, jdu na to.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-EMAIL-ERR_title"] = "Bez zadání údajů emailu to nepůjde.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-EMAIL-ERR_text"] = "Zadejte paramtery emaily a přílohu, která se bude odesílat";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-EMAIL-ERR_button"] = "Jdu na to!";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY_title"] = "Co by to bylo za soutěž bez výher?";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY_text"] = "O co budou lidé ve vaši aplikaci soutěžit? Nahrát můžete libovolný počet výher. Klikněte na ikonu pro umístění obrázku a zadejte potřebné údaje o výhře.<br /> Až budou výhry připravené, pokračujte na &quot;Pokračovat&quot;.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY_button"] = "Ok, jdu na to.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY-ERR_title"] = "Výhry jsou základ!";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY-ERR_text"] = "Než budete pokračovat, zadejte nejméně jednu výhru,<br /> o kterou budou soutěžící ve vaší soutěži bojovat.<br /> Pak přejděte na &quot;Další stranu&quot;. ";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-VYHRY-ERR_button"] = "Přidat výhry";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-PODMINKY-SOUTEZE_title"] = "Soutěžní podmínky";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-PODMINKY-SOUTEZE_text"] = "Každá soutěž má svá pravidla.<br /> Připravit taková pravidla stojí čas i peníze. Proto jsme vám tuto povinnost maximálně zjednodušili. Stačí když v dokumentu přepíšete žlutě podbarvená pole relevantními údaji o vaší společnosti. Pak pokračujte na &quot;Další stranu&quot; k poslednímu kroku.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-PODMINKY-SOUTEZE_button"] = "Ok, jdu na to.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FORMULAR_title"] = "Výherní formulář";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FORMULAR_button"] = "Ok, jdu na to.";

/* obecne pro setting vsech app */
$CONF_XTRA["texty"]["cs"]["link-obchodni-podminky"] = "Obchodní podmínky";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_si_tema"] = "Vyberte si téma pro vaši aplikaci";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_si_skin"] = "Zvolte skin vaší aplikace";
$CONF_XTRA["texty"]["cs"]["setting-nahrajte_si_vlastni_obrazky"] = "Přidejte do grafiky vlastní prvky";
$CONF_XTRA["texty"]["cs"]["setting-nahrajte_si_vlastni_obrazky-popis"] = "Nahrajte například logo vaší firmy a přidejte ho do grafiky vaší aplikace. Pro nejlepší výsledek doporučujeme nahrávat obrázky ve formátu *.png ";
$CONF_XTRA["texty"]["cs"]["setting-nahrajte_si_vlastni_obrazky-submit"] = "Nahrát nový obrázek";
$CONF_XTRA["texty"]["cs"]["setting-nahrajte_si_vlastni_obrazky-picedit_submit"] = "Nahrát";
$CONF_XTRA["texty"]["cs"]["setting-nahrajte_si_vlastni_obrazky-zatim-nemate-nic"] = "Zatím nic";
$CONF_XTRA["texty"]["cs"]["setting-label_pocet_kusu_vyhra"] = "Počet ks";
$CONF_XTRA["texty"]["cs"]["setting-zadejte_vyhru"] = "Přidejte výhru";
$CONF_XTRA["texty"]["cs"]["setting-upravte_vyhru"] = "Upravte výhru";
$CONF_XTRA["texty"]["cs"]["setting-zvolte_umisteni"] = "umístění";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_umisteni"] = "Vyberte umístění";
$CONF_XTRA["texty"]["cs"]["setting-doporuceni_pocet_vyher"] = "Doporučujeme přidat v součtu alespoň 20 výher";
$CONF_XTRA["texty"]["cs"]["setting-nastavte_pravdepodobnost_vyhry"] = "Jaká bude pravděpodobnost,<br> že soutěžící uhádne kód této výhry?";
$CONF_XTRA["texty"]["cs"]["setting-placeholder_zadejte_nazev_vyhry"] = "Zadejte název výhry";
$CONF_XTRA["texty"]["cs"]["setting-label_pocet_kusu_vyhra"] = "Počet ks";
$CONF_XTRA["texty"]["cs"]["setting-input_file_title_vyhra"] = "Nahrajte obrázek výhry. <span>(Doporučená velikost obrázku 800x400px)</span>";
$CONF_XTRA["texty"]["cs"]["setting-input_file_title_vyhra_static"] = "Nahrajte obrázek výhry. <span>(Doporučená velikost obrázku 700x700px)</span>";
$CONF_XTRA["texty"]["cs"]["setting-rozsirene_moznosti_vyher-title"] = "Rozšířené možnosti výher";
$CONF_XTRA["texty"]["cs"]["setting-rozsirene_moznosti_vyher-label-umoznit_opakovanou_vyhru-text"] = "Umožnit uživateli opakovaně vyhrát";
$CONF_XTRA["texty"]["cs"]["setting-rozsirene_moznosti_vyher-label-umoznit_opakovanou_vyhru-info"] = "(max 1x denně - volba platí pro všechny výhry v soutěži)";
$CONF_XTRA["texty"]["cs"]["setting-create_new_prize"] = "Kliknutím zadáte novou výhru";
$CONF_XTRA["texty"]["cs"]["setting-input_file_title_baner"] = "Nahrajte obrázek baneru";
$CONF_XTRA["texty"]["cs"]["setting-placeholder_zadejte_url_baneru"] = "Zadejte url";

$CONF_XTRA["texty"]["cs"]["setting-confirm_delete_price"] = "Opravdu smazat cenu?";
$CONF_XTRA["texty"]["cs"]["form_check-err_sorry_you_are_premium"] = "Tato objednávková stránka je pouze pro nové členy. Pokud chcete za zvýhodněnou cenu získat členství ve Studio x51 Academy, napište nám na email kamca@socialsprinters.cz. Kamča vám členství obratem vytvoří  :-)";
$CONF_XTRA["texty"]["cs"]["form_check-err_sorry_you_are_premium-trial"] = "Tato objednávková stránka je pouze pro nové členy.";
$CONF_XTRA["texty"]["cs"]["setting-err_nezadany_vyhry"] = "Přidejte soutěžní ceny :-)";
$CONF_XTRA["texty"]["cs"]["setting-err_zvolte_pravdepodobnost_vyhry"] = "Zvolte pravděpodobnost výhry";
$CONF_XTRA["texty"]["cs"]["setting-err_zadejte_nazev_vyhry"] = "Zadejte název výhry";
$CONF_XTRA["texty"]["cs"]["setting-err_vyberte_obrazek_vyhry"] = "Vyberte obrázek výhry";
$CONF_XTRA["texty"]["cs"]["setting-stop_admin-vyhry"] = "<span>Soutěž byla spuštěna.</span> Výhry nelze upravit v probíhající soutěži.";
$CONF_XTRA["texty"]["cs"]["setting-err_vyberte_titulek_og"] = "Zadejte titulek";
$CONF_XTRA["texty"]["cs"]["setting-err_zadejte_url_baneru"] = "Zadejte url baneru";
$CONF_XTRA["texty"]["cs"]["setting-err_vyberte_obrazek_baneru"] = "Vyberte obrázek baneru";
$CONF_XTRA["texty"]["cs"]["setting-err_vyberte_obrazek_og"] = "Vyberte obrázek";
$CONF_XTRA["texty"]["cs"]["ss_sign"] = "Vytvořte své firmě Facebook aplikaci za méně než 5 minut";

/* info mail o novem vyherci! */
$CONF_XTRA["texty"]["cs"]["setting-email_info-vyherce-subject"] = "Nový výherce v soutěži!";
$CONF_XTRA["texty"]["cs"]["setting-email_info-vyherce-body1"] = "<p>Ve vaší soutěži #add_name_soutez# umístěné na Facebook stránce #add_fb_page_url# máte nového výherce!</p>"; // pozor znaky # zachovat, slouzi k dodatecne zamene za real. data
$CONF_XTRA["texty"]["cs"]["setting-email_info-vyherce-nazev-vyhry-title"] = "Název výhry:";
$CONF_XTRA["texty"]["cs"]["setting-email_info-vyherce-kontakt-title"] = "Kontaktní údaje";
$CONF_XTRA["texty"]["cs"]["setting-email_info-vyherce-body2"] = "<p>Nyní výherce kontaktujte a domluvte se s ním na předání výhry.</p>
<p>SocialSprinters TIP: Doporučujeme výherce kontaktovat co nejdříve. Pokud soutěžící vyhrál například slevu na nákup, je vhodné časově omezit její platnost a tím ho motivovat k okamžitému využití.</p>";
/* /info mail o novem vyherci! */

/* odberatel */
$CONF_XTRA["texty"]["cs"]["odberatel-nazev"] = "Název společnosti";
$CONF_XTRA["texty"]["cs"]["odberatel-ulice"] = "Ulice";
$CONF_XTRA["texty"]["cs"]["odberatel-mesto"] = "Město";
$CONF_XTRA["texty"]["cs"]["odberatel-psc"] = "PSČ";
$CONF_XTRA["texty"]["cs"]["odberatel-ic"] = "IČ";
$CONF_XTRA["texty"]["cs"]["odberatel-dic"] = "DIČ";
$CONF_XTRA["texty"]["cs"]["odberatel-platce_dph"] = "Plátce DPH";
$CONF_XTRA["texty"]["cs"]["odberatel-telefon"] = "Telefon";
$CONF_XTRA["texty"]["cs"]["odberatel-email"] = "E-mail";
$CONF_XTRA["texty"]["cs"]["odberatel-stat"] = "Stát";

/* / obecne pro setting vsech app */



/* nahledy app */
$CONF_XTRA["nahled_aplikace"] = array(1=>857, 2=>52, 3=>1369, 4=>395, 6=>399, 7=>320, 8=>2007);
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_link_app2fb"] = "Jak vypadá aplikace na Facebook stránce?";
// app 1 - fotosoutez
$CONF_XTRA["nahled_app_url_1"] = "https://x51.cz/apps/ssp-fotosoutez?aplikace_id=".$CONF_XTRA["nahled_aplikace"][1];
$CONF_XTRA["nahled_app_fb_url_1"] = "https://www.facebook.com/socialsprinters/app_485277671673055";
// app 2 - trezor
$CONF_XTRA["nahled_app_url_2"] = "https://x51.cz/apps/trezor2/".$CONF_XTRA["nahled_aplikace"][2]."/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][2];
$CONF_XTRA["nahled_app_fb_url_2"] = "https://www.facebook.com/socialsprinters/app_665159800296479";
// app 3 - kviz
$CONF_XTRA["nahled_app_url_3"] = "https://x51.cz/apps/ssp-kviz/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][3];
$CONF_XTRA["nahled_app_fb_url_3"] = "https://www.facebook.com/socialsprinters/app_473884249487231";
// app 4 - zalozka
$CONF_XTRA["nahled_app_url_4"] = "https://x51.cz/apps/ssp-zalozka/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][4];
$CONF_XTRA["nahled_app_fb_url_4"] = "https://www.facebook.com/socialsprinters/app_350646991792822";
// app 6 - databaze
$CONF_XTRA["nahled_app_url_6"] = "https://x51.cz/apps/ssp-zisk-zdarma/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][6];
$CONF_XTRA["nahled_app_fb_url_6"] = "https://www.facebook.com/socialsprinters/app_873392336081351";
// app 7 - kolo stesti
$CONF_XTRA["nahled_app_url_7"] = "https://x51.cz/apps/ssp-kolo-stesti/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][7];
if(strpos($_SERVER["SCRIPT_NAME"], "aa-test"))
	$CONF_XTRA["nahled_app_url_7"] = "https://x51.cz/apps/ssp-kolo-stesti-test/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][7];
$CONF_XTRA["nahled_app_fb_url_7"] = "https://www.facebook.com/socialsprinters/app_726722437473534";
// app 8 - instagram
$CONF_XTRA["nahled_app_url_8"] = "https://x51.cz/apps/ssp-instagram/?aplikace_id=".$CONF_XTRA["nahled_aplikace"][8];
$CONF_XTRA["nahled_app_fb_url_8"] = "https://www.facebook.com/socialsprinters/?sk=app_969670826454383";

// texty app 1 - fotosoutez
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_1"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_1'>Fotosoutěž</div>

<h3>Jak funguje</h3>
<p>Vyhrají, kterým se podaří nasbírat ve vámi zvoleném termínu konání soutěže co nejvíce hlasů. Jako administrátor to vše jednoduše nastavíte spolu se vzhledem v přehledné administraci soutěže. Soutěž spustíte do několika minut.</p>

<p>Pokud soutěžící uhádne výherní kód, zobrazí se mu výhra a informace o jejím předání. Jako administrátor to vše jednoduše nastavíte spolu se vzhledem v přehledné administraci soutěže. Soutěž spustíte do několika minut.</p>

<h3>Doporučené výhry</h3>
<p>Vaše služby a produkty, slevy (např. 2-15%) na nákup, případně reklamní předměty.</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Fotografie, nebo ilustrační obrázky výher</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Organicky šíříte značku v cílové skupině (soutěžící jsou vašimi ambasadory)</li>
<li>Pomocí soutěže zvýšíte aktivitu vašich stávajících fanoušků</li>
<li>Motivujete soutěžící k okamžitému nákupu</li>
<li>Zvýšíte loajalitu fanoušků k vaší značce</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_1"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";



// texty app 2 - trezor
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_2"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_2'>Tipni kód a vyhraj</div>

<h3>Jak funguje</h3>
<p>Soutěžící mají za úkol uhádnout jeden z výherních kódů,
který otevře víko truhly plné cen. Každou hodinu
má soutěžící 3 pokusy na tipnutí správného kódu.</p>

<p>Pokud soutěžící uhádne výherní kód, zobrazí se mu výhra
a informace o jejím předání. Jako administrátor to vše
jednoduše nastavíte spolu se vzhledem v přehledné
administraci soutěže. Soutěž spustíte do několika minut.</p>

<h3>Doporučené výhry</h3>
<p>Slevy (např. 2-15%) na nákup. Dále také reklamní předměty, vaše hlavní, či doplňkové služby nebo produkty.</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Fotografie, nebo ilustrační obrázky výher</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Zábavnou formou rozdáte fanouškům slevy
a přitom zachováte exkluzivitu vaší značky</li>
<li>Pomocí soutěže zvýšíte aktivitu vašich
stávajících fanoušků</li>
<li>Motivujete soutěžící k okamžitému nákupu</li>
<li>Zvýšíte loajalitu fanoušků k vaší značce</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_2"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";


// texty app 3 - kviz;
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_3"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_3'>Tipni správnou odpověď!</div>

<h3>Jak funguje</h3>
<p>Zabavte fanoušky na vaší stránce a připravte pro ně test. Zvolte si libovolný počet otázek a ověřte si jejich znalosti. Je jen na vás jakou formou test pojmete.  Může mít formu zábavnou nebo ji proměňte ve vědomostní zkoušku, která bude uživatele vybízet ke sdílení jeho výsledku a tím pádem i vaší aplikace. Pozadí aplikace si můžete změnit nebo nahrát své vlastní.</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Vlastní nebo ilustrační fotografie</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Uživatelé sdílejí své výsledky mezi své přátele a tím se aplikace přirozeně šíří</li>
<li>Zvyšujete povědomí o vaší firmě a můžete edukovat zákazníky o vašich produktech a službách</li>
<li>Prostřednictvím aplikace získáváte nové fanoušky</li>
<li>Získáte nové emailové adresy (např. pro rozesílku newsletteru)</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_3"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";



// texty app 4 - zalozka;
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_4"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_4'>Záložka</div>

<h3>Jak funguje</h3>
<p>Každý den na vaši stránku na Facebooku chodí desítky, možná stovky lidí, kteří se o vás chtějí dozvědět více informací. Pomocí tohoto typu aplikace máte možnost sdělit klíčové informace o vás nebo vaší firmě na jednom místě. Můžete zde ukázat své produkty, nebo zákazníky odkázat na konkrétní službu. Jako administrátor nastavíte kompletní vzhled vaší záložky (včetně tlačítka, které může odkazovat na vaše webové stránky). Nahrajete vlastní fotografie, upravíte texty a za pár minut máte profesionální záložku s nejdůležitějšími informacemi pro vaše fanoušky.</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Vlastní nebo ilustrační fotografie produktů, služeb, atd.</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Sdělíte všechny potřebné informace návštěvníkovi v přehledné záložce</li>
<li>Tlačítkem v záložce můžete návštěvníka přivést na vaše webové stránky</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_4"] = "
<p>Pořizovací cena: <span class='price'>první záložka zdarma, druhá za %price_app_per_month% Kč/měsíčně</span></p>
";

// texty app 6 - budovani databaze
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_6"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_6'>Budování databáze</div>

<h3>Jak funguje</h3>
<p>Získejte kontakt na vaše zákazníky. Skrze tuto aplikaci můžete jednoduchým způsobem získat telefon, email, jméno a příjmení a další údaje o vašich potenciálních zákaznících. Výměnou, za obsah zdarma.</p>
<p>Stačí vytvořit PDFko, e-book, nebo článek a ten do aplikace umístit. Všichni lidé, kteří budou chtít vámi nabízený obsah zdarma získat, musí zadat nejprve své údaje. Jednodušší už to být nemůže. S tímto typem aplikace si budete jisti, že sbíráte kvalitní kontakty na vaše skutečné potenciální zákazníky</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>PDF soubor s extrémně hodnotnými informacemi pro vaše zákazníky. Čím hodnotnější obsah PDF souboru pro vaše zákazníky bude, tím více z nich vám na sebe zanechá svůj kontakt</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Vybudujete nebo rozšíříte svou emailovou databázi</li>
<li>Máte možnost vyžádat si také telefonní čísla a další údaje</li>
<li>Zvýšíte loajalitu fanoušků k vaší značce</li>
<li>Budete se profilovat jako expert na danou oblast</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_6"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";

// texty app 7 - kolo stesti;
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_7"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_7'>Kolo štěstí</div>

<h3>Jak funguje</h3>
<p>Soutěžící mají možnost roztočit každý den kolo, a když mají štěstí, vyhrají. Další pokusy na roztočení kola štěstí získají za sdílení vaší Stránky.
Pokud soutěžícímu padne na kole políčko „vyhráváš“, zobrazí se mu výhra
a informace o jejím předání. Jako administrátor to vše
jednoduše nastavíte spolu se vzhledem v přehledné
administraci soutěže. Soutěž spustíte do několika minut.
</p>

<h3>Doporučené výhry</h3>
<p>Slevy (např. 2-15%) na nákup. Dále také reklamní předměty, vaše hlavní, či doplňkové služby nebo produkty.</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Fotografie, nebo ilustrační obrázky výher</li>
<li>Správcovství na vaší Facebook stránce</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Tato soutěž může na vaší stránce běžet nepřetržitě i několik měsíců</li>
<li>Rozdáte fanouškům slevy a přitom zachováte exkluzivitu vaší značky</li>
<li>Soutěžící vám pomáhají šířit povědomí o vaší značce napříč Facebookem</li>
<li>Motivujete soutěžící k okamžitému nákupu</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_7"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";

// texty app 8 - instagram;
$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_8"] = "
<h2>Detail aplikace</h2>
<h3>Typ aplikace</h3>
<div class='name_app app_8'>Instagram</div>

<h3>Jak funguje</h3>
<p>
Každý den na vaši stránku na Facebooku chodí desítky, možná stovky lidí, kteří se o vás chtějí dozvědět více informací. Pomocí tohoto typu aplikace máte možnost ukázat jim více ze svého soukromí. Ukažte svým fanouškům váš Instagramový účet přímo na Facebook stránce. Aplikace se propojí s vaším Instagramový účtem, kde máte možnost výběrů zobrazení vašeho profilu nebo vyhledání čehokoli pod daným hashtagem. Jako administrátor nastavíte kompletní vzhled vaší aplikace Instagram (včetně tlačítka SLEDOVAT, díky kterému váš Instagramový účet získá větší počet sledujících). Upravíte texty a za pár minut máte profesionální aplikaci Instagram hotovou.
</p>

<h3>Co budete potřebovat</h3>
<ol>
<li>Správcovství na vaší Facebook stránce</li>
<li>Instagramový profil</li>
</ol>

<h3>Výhody</h3>
<ul>
<li>Ukažte svým fanouškům a návštěvníkům vaše fotky z Instagramového účtu</li>
<li>Díky tlačítku “sledovat“ získá váš Instagramový účet větší počet sledujících</li>
</ul>";

$CONF_XTRA["texty"]["cs"]["setting-text_nahled_app_porizovaci_cena_8"] = "
<p>Pořizovací cena: <span class='price'>%price_app_per_month% Kč/měsíc</span></p>
";




$CONF_XTRA["texty"]["cs"]["setting-button_nahled_app"] = "Přizpůsobte si vzhled aplikace";

/* nahledy app */

/* obecne primo u aplikaci */
$CONF_XTRA["texty"]["cs"]["form_check-err_zadejte-povinne_pole"] = "Zadejte:";
$CONF_XTRA["texty"]["cs"]["form_check-err_zadejte-platny_email"] = "Zadejte platný email";
/* sklonovani obecne */
	// cs lang / default
	$CONF_XTRA["texty"]["cs"]["kod"] = "kód"; // 1: kod
	$CONF_XTRA["texty"]["cs"]["kodu"] = "kódů";  // 0 nebo >=5: kodu
	$CONF_XTRA["texty"]["cs"]["kody"] = "kódy"; // zbytek: kody
	// cena
	$CONF_XTRA["texty"]["cs"]["cena"] = "cena"; // 1: cen
	$CONF_XTRA["texty"]["cs"]["cen"] = "cen";  // 0 nebo >=5: cenu
	$CONF_XTRA["texty"]["cs"]["ceny"] = "ceny"; // zbytek: ceny
	// minuta
	$CONF_XTRA["texty"]["cs"]["minuta"] = "minuta"; // 1: minut
	$CONF_XTRA["texty"]["cs"]["minut"] = "minut";  // 0 nebo >=5: minutu
	$CONF_XTRA["texty"]["cs"]["minuty"] = "minuty"; // zbytek: minuty

/* /sklonovani obecne */

	// jmeno a prijmeni
	$CONF_XTRA["texty"]["cs"]["jmeno_a_prijmeni"] = "Jméno a příjmení"; //
	// Korespondenční adresa
	$CONF_XTRA["texty"]["cs"]["kor_adresa"] = "Korespondenční adresa"; //
	// email
	$CONF_XTRA["texty"]["cs"]["email"] = "E-mail"; //
	// telefon
	$CONF_XTRA["texty"]["cs"]["telefon"] = "Telefon"; //

	// Gratulujeme
	$CONF_XTRA["texty"]["cs"]["gratulujeme"] = "Gratulujeme! Vyhráváš!"; //
	// Neuhadl jsi
	$CONF_XTRA["texty"]["cs"]["neuhadl_jsi"] = "Neuhádl jsi!"; //
	// Neuhadl jsi
	$CONF_XTRA["texty"]["cs"]["uspesne_odeslano"] = "Úspěšně odesláno!"; 
	// title counter - Dalsi pokus ziskaz za ...
	$CONF_XTRA["texty"]["cs"]["dalsi_pokusy"] = "Další 3 pokusy získáš za"; //
	// end
	$CONF_XTRA["texty"]["cs"]["soutez_skoncila"] = "Soutěž skončila"; //


/* / obecne primo u aplikaci */

/* demo aplikace */
$CONF_XTRA["texty"]["cs"]["demo-app_button_dalsi-info"] = "Další informace o aplikaci";
$CONF_XTRA["texty"]["cs"]["demo-app_cena-najem"] = "Cena za pronájem aplikace";
$CONF_XTRA["texty"]["cs"]["demo-app_cena_mena-Kc"] = "Kč";
$CONF_XTRA["texty"]["cs"]["demo-app_cena_delka-mesic"] = "měsíc";
$CONF_XTRA["texty"]["cs"]["demo-app_head-title_vyzkousejte-aplikace"] = "Vyzkoušejte si jak tato aplikace funguje";
$CONF_XTRA["texty"]["cs"]["demo-app_title_typ-aplikace"] = "Typ aplikace:";
$CONF_XTRA["texty"]["cs"]["demo-app_novinka"] = "Novinka";
$CONF_XTRA["texty"]["cs"]["demo-app_1_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, nastavíte výhry a soutěž spustíte do několika minut na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_2_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, nastavíte výhry a soutěž spustíte do několika minut na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_3_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, přidáte libovolný počet otázek a odpovědí a do několika minut spustíte na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_4_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, přidáte text, obrázky, nebo video a informační záložku do několika minut spustíte na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_6_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, přidáte text, soubor ke stažení a do několika minut začnete sbírát emailové adresy zákazníků na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_7_descr"] = "Jako administrátor si jednoduše přizpůsobíte vzhled celé aplikace, změníte hlavní motiv, nastavíte výhry a soutěž spustíte do několika minut na vaší Facebookové stránce.";
$CONF_XTRA["texty"]["cs"]["demo-app_8_descr"] = "Aplikace se propojí s vaším Instagramový účtem, kde máte možnost výběrů zobrazení vašeho profilu nebo vyhledání klíčového slova pod daným hashtagem.";

/* /demo aplikace */

/* TEXTY APLIKACE 1 FOTOSOUTEZ */
$CONF_XTRA["texty"]["cs"]["setting-poradi_misto_v_soutezi"] = "místo";
$CONF_XTRA["texty"]["cs"]["setting-order_nejlepsi"] = "Nejlepší";
$CONF_XTRA["texty"]["cs"]["setting-order_nejnovejsi"] = "Nejnovější";
$CONF_XTRA["texty"]["cs"]["setting-order_moje_fotky"] = "Moje fotky";
$CONF_XTRA["texty"]["cs"]["setting-moje_fotky-title"] = "Vámi nahrané fotografie";
$CONF_XTRA["texty"]["cs"]["setting-moje_fotky-subtitle"] = "Sdílej své fotografie s přáteli a získej pro ně co nejvíce hlasů.<br /> Čím více hlasů vaše fotografie získají, tím blíže jste k hlavní výhře soutěže.";
$CONF_XTRA["texty"]["cs"]["setting-moje_fotky-zadne_fotky"] = "Nemáte nahrány žádné fotografie.";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_barvu_ovladacich_prvku"] = "Vyberte barevný přechod";
$CONF_XTRA["texty"]["cs"]["setting-hlavni_motivacni_text"] = "<p>Zapojte se do soutěže a vyhrajte některou z těchto cen</p>"; 
$CONF_XTRA["texty"]["cs"]["setting-confirm_opravdu_smazat_fotografii"] = "Opravdu smazat tuto fotografii?"; 

$CONF_XTRA["texty"]["cs"]["setting-title-datum_ukonceni_souteze"] = "Datum ukončení soutěže"; 
$CONF_XTRA["texty"]["cs"]["setting-subtitle-datum_ukonceni_souteze"] = "Doporučená délka soutěže je 7 - 10 dní"; 
$CONF_XTRA["texty"]["cs"]["setting-label-vyberte-datum_cas"] = "Zadejte datum a čas"; 
$CONF_XTRA["texty"]["cs"]["setting-title-reg_form"] = "Už zbývá jen jeden krok"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-jmeno"] = "Vaše jméno a příjmení"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-email"] = "Emailová adresa"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-telefon"] = "Telefon"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-popis_fotografie"] = "Popis fotografie"; 
$CONF_XTRA["texty"]["cs"]["setting-button_pokracovat"] = "Pokračovat"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-label_souhlas1"] = "Souhlasím s "; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_form-label_souhlas2"] = "podmínkami soutěže"; 
$CONF_XTRA["texty"]["cs"]["setting-input-reg_err-placeholder"] = "Zaškrtněte souhlas s podmínkami soutěže"; 
$CONF_XTRA["texty"]["cs"]["setting-success_share-title"] = "Fotka úspěšně přidána. Sbírejte pro ni hlasy!"; 
$CONF_XTRA["texty"]["cs"]["setting-success_share-txt"] = "Sdílej svůj odkaz s přáteli a získej pro ni co nejvíce hlasů.<br /> Čím víc hlasů tvá fotografie získá, tím blíže si k hlavní výhře soutěže."; 
$CONF_XTRA["texty"]["cs"]["setting-hlas_done-title"] = "Díky za váš hlas!"; 
$CONF_XTRA["texty"]["cs"]["setting-hlas_done-txt"] = "Sdílejte fotografii pro kterou jste hlasovali se svými přáteli a<br /> pomozte ji dostat se na vrchol tabulky soutěže."; 

$CONF_XTRA["texty"]["cs"]["setting-fb_share"] = "Sdílej odkaz na Facebook"; 
$CONF_XTRA["texty"]["cs"]["setting-fb_share-friends"] = "Sdílej odkaz s přáteli"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovat-title"] = "Hlasuj pro tuto fotografii"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovat-text_big_button"] = "Hlasovat"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovat-zrusit-text_big_button"] = "Zrušit hlas"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovat-no_photo"] = "Foto nenalezeno!"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovani-short_url-og_title"] = "Hlasujte v soutěži pro tuto fotografii!"; 
$CONF_XTRA["texty"]["cs"]["setting-hlasovani-short_url-og_description"] = "A nebo nahrajte vlastní fotografii a zabojujte o skvělé výhry v soutěži!"; 


$CONF_XTRA["texty"]["cs"]["setting-hlavni_nazev_souteze"] = "<h1>Hlavní název nebo<br> nadpis soutěže</h1>";
$CONF_XTRA["texty"]["cs"]["setting-hlavni_popis_souteze"] = "<h2>Přidejte fotku do soutěže</h2>
<p>Přidej svou fotografii soutěže a vyhraj některou z hotnotných cen. Svou fotografii můžeš nahrát fotografii z počítače, mobilu nebo z Instagramu</p><p>Nahraj libovolný počet fotografíí a sbírej pro ně hlasy. Čím víc fotografií nahraješ, tím větší šansi na výhru získáš. <br> Tak hurá do toho!</p>";
$CONF_XTRA["texty"]["cs"]["setting-info_title-soutez_skocila"] = "<h2>Soutěž skončila</h2>";
/* / TEXTY APLIKACE 1 FOTOSOUTEZ */

/* TEXTY APLIKACE 2 TREZOR */
$CONF_XTRA["texty"]["cs"]["setting-dalsi_3_pokusy_za"] = "Další 3 pokusy získáš za";
$CONF_XTRA["texty"]["cs"]["setting-dalsi_3_pokusy_60_minut"] = "60 minut";
$CONF_XTRA["texty"]["cs"]["setting-dalsi_3_pokusy_nahrat_baner_1"] = "NAHRÁT BANER č. 1";
$CONF_XTRA["texty"]["cs"]["setting-dalsi_3_pokusy_nahrat_baner_2"] = "NAHRÁT BANER č. 2";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-BANERY_title"] = "Zkraťte soutěžícím čekání";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-BANERY_text"] = "Soutěžící mají každých 60 minut 3 pokusy na uhádnutí výherního kódu. Když se jim to nepodaří, dostanou se na tuto čekací stránku. Zde jim můžete zkrátit čekaní tím, že je skrze grafický baner nebo obrázek pošlete například na vaše webové stránky, kde si můžou produkt zakoupit nebo o něm získat více informací.<br /><br />(Pokud nechcete soutěžícím zkrátit čekání, nechte pole pro obrázek prázdné a rovnou pokračujte &quot;Na další stranu&quot;)";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-BANERY_button"] = "Ok, jdu na to.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FORMULAR_APP-2_text"] = "Jaká pole mají vyplňovat soutěžící v případě, že trefí svým kódem výhru? Políčka ve formuláři můžete libovolně editovat kliknutím. Následné změny uložte a pokračujte na &quot;Další stranu&quot;.";
/* / TEXTY APLIKACE 2 TREZOR */

/* TEXTY APLIKACE 3 KVIZ */
$CONF_XTRA["SIZE-3-vysledky"]["ThumbSizeMaxWidth"] = 206;
$CONF_XTRA["SIZE-3-vysledky"]["ThumbSizeMaxHeight"] = 206;
$CONF_XTRA["SIZE-3-vysledky"]["BigImageMaxWidth"] = 660;
$CONF_XTRA["SIZE-3-vysledky"]["BigImageMaxHeigth"] = 300;
$CONF_XTRA["SIZE-3-otazky"]["ThumbSizeMaxWidth"] = 674;
$CONF_XTRA["SIZE-3-otazky"]["ThumbSizeMaxHeight"]  = 300;
$CONF_XTRA["SIZE-3-otazky"]["BigImageMaxWidth"] = 800;
$CONF_XTRA["SIZE-3-otazky"]["BigImageMaxHeigth"] = 800;

$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-nazev_kvizu"] = "<h1>Název testu</h1>";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-button_spustit-kviz"] = "Spustit test";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-nazev_kvizu"] = "<h1>Název testu</h1>";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-APP-3_subtitle"] = "<p>Test pomocí deseti rychlých otázek vyhodnotí, jaké je Vaše podnikatelské zaměření. Nejde o žádnou rozsáhlou psychologickou analýzu, výsledek proto berte s rezervou</p><p>Test vám na základě vašich odpovědí vyhodnotí, dvě dominantní podnikatelské charakterové oblasti z výše nastíněných čtyř a poradí několik hlavních poznatků o tom, co v podnikání právě vaší kombinaci svědčí a na co si naopak dávat největší pozor.</p>";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-title_edit_vysledky"] = "Přidejte výsledek testu a zvolte procento správných odpovědí pro jeho dosažení";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-title_vysledek"] = "<p>Napište nadpis tohoto výsledku</p>";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-text_vysledek"] = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla purus sapien, pharetra sit amet iaculis ac, consequat et est. Aenean quis mauris congue, egestas lacus id, posuere tellus. In placerat molestie arcu a vulputate. Nam tincidunt sagittis mi vitae vestibulum. Mauris augue sapien, cursus vel tortor sed, imperdiet fermentum justo. Aenean leo metus, venenatis vitae pulvinar eu, tristique efficitur ipsum.";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-pridat_dalsi_vysledek"] = "Přidat další výsledek";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-vyledky-slider-info"] = "procento správných výsledků";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-title_edit_otazky"] = "Počet otázek ve vašem testu";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_text-title_otazka"] = "<p>Zde napište text vaší otázky</p>";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-info_text-add_photo_otazky"] = "Přidejte k otázce ilustrační obrázek";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-typ_otazka_1"] = "Jedna správná odpověď";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-typ_otazka_2"] = "Více správných odpověďí";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-title_edit_odpovedi"] = "Přidejte možné odpovědi a označte správnou/správné";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-pridat_dalsi_odpoved"] = "Přidat další odpověd";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-default_placeholder_odpoved"] = "Zde napište možnou odpověd";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-button_dalsi_otazka"] = "Další otázka";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-kviz-vysledek_info_text"] = "Tvůj výsledek je:";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-kviz-vysledek_button_sdilet"] = "Sdílet s přáteli";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-kviz-vysledek_opakovat_test"] = "Opakovat test";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_APP-3_pocet-dokoncecnych-kvizu"] = "Počet dokončených<br /> testů";
$CONF_XTRA["texty"]["cs"]["dashboard-statistika_APP-3_prumer-spravnych-odpovedi"] = "Průměr správných<br /> odpovědí";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-HELP-TEXT_SET-VYSLEDEK_title"] = "Přidejte výsledek testu";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-HELP-TEXT_SET-VYSLEDEK_text"] = "Přidejte libovolný počet výsledků testu, upravte texty a nastavte při jaké procentuální úspěšnosti se daný výsledek bude zobrazovat. Až budete s nastavením spokojeni, přejděte na další stránku kliknutím na tlačítko &quot;Pokračovat&quot;";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-HELP-TEXT_SET-OTAZKY_title"] = "Přidejte otázky do vašeho testu";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-HELP-TEXT_SET-OTAZKY_text"] = "Zvolte počet otázek a poté pro každou nastavte název, zvolte fotografii a přidejte možné odpovědi. Správnou odpověď nebo odpovědi označte. Až budete s nastavením spokojeni, přejděte na další stránku kliknutím na tlačítko &quot;Pokračovat&quot;";
$CONF_XTRA["texty"]["cs"]["setting-APP-3-test_spusten"] = "Test spuštěn";
/* /TEXTY APLIKACE 3 KVIZ */

/* TEXTY APLIKACE 4 ZALOZKA */
/* default test pro vsechny skiny - dalsi u kazdeho skinu v default-texts.php (napr: /skiny/master-skin1/default-texts.php )*/
$CONF_XTRA["texty"]["cs"]["setting-default_text-title"] = "<h1>Farmářské trhy</h1><p>Čerstvá zelenina až domů na váš stůl</p>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-long_text"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla purus sapien, pharetra sit amet iaculis ac, consequat et est. Aenean quis mauris congue, egestas lacus id, posuere tellus. In placerat molestie arcu a vulputate. Nam tincidunt sagittis mi vitae vestibulum. Mauris augue sapien, cursus vel tortor sed, imperdiet fermentum justo. Aenean leo metus, venenatis vitae pulvinar eu, tristique efficitur ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in lorem eros. Etiam sit amet eleifend purus. Etiam vitae sem maximus, laoreet lacus et, pellentesque odio. Integer suscipit maximus mauris vitae tincidunt. Cras dignissim magna eu dapibus ultricies";
$CONF_XTRA["texty"]["cs"]["setting-button_dalsi-informace"] = "Další informace";
/* /TEXTY APLIKACE 4 ZALOZKA */

/* TEXTY APLIKACE 6 BUDOVANI DATABAZE */
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_title"] = "<h1><span style='%setting-color-uvod_text%'>Tento e-book zdarma odhaluje<br /> 4 (extrémně levné) způsoby jak budovat e-mailovou databázi pomocí Facebooku</span></h1>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_subtitle"] = "<p><span style='color:#829eb3'>Zadejte svou e-mailovou adresu pro stažení e-booku</span></p>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_bezpecnost2"] = $CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_bezpecnost"] = "<p><span style='color:#829eb3'><strong>Zabezpečení:</strong> Nesnášíme SPAM a slibujeme, že vaši adresu<br /> uchováme v bezpečí</span></p>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_title2"] = "<h2>Objevte všechny <span style='color:#FFA500'>4 geniálně jednoduché</span> a účinné způsoby, kterými získáte e-mailové adresy vašich fanoušků na Facebooku</h2>";

$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_emailsendinfo"] = "<h2>E-book byl odeslán na váš email</h2>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_title3"] = "<h2>Udělejte další krok...</h2>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_substitle3a"] = "<p class=\"orange\">Všechno, co jsme slíbili, vám za chvilku přijde do vaší schránky.</p>";
$CONF_XTRA["texty"]["cs"]["setting-default_text-APP-6_substitle3b"] = "<p>Pomůže vám mega rychle vybudovat extrémně kvalitní e-mailovou<br /> databázi potenciálních zákazníků z Facebooku</p>";

$CONF_XTRA["texty"]["cs"]["setting-zadejte_text_tlacitka"] = "Zadejte text tlačítka";
$CONF_XTRA["texty"]["cs"]["setting-zadejte_url_tlacitka"] = "Zadejte url odkazu";
$CONF_XTRA["texty"]["cs"]["setting-vyberte_barvu_tlacitka"] = "Vyberte si barvu tlačítka";

$CONF_XTRA["texty"]["cs"]["setting-email_setting-nastaveni_parametru_emailu"] = "Nastavení parametrů e-mailu";
$CONF_XTRA["texty"]["cs"]["setting-email_setting-nastaveni_parametru_emailu-from"] = "Odesílatel (vaše e-mailová adresa)";
$CONF_XTRA["texty"]["cs"]["setting-email_setting-nastaveni_parametru_emailu-predmet"] = "Předmět";
$CONF_XTRA["texty"]["cs"]["setting-email_setting-nastaveni_parametru_emailu-obsah"] = "Obsah";
$CONF_XTRA["texty"]["cs"]["setting-email_setting-nastaveni_parametru_emailu-attachment"] = "Soubor ke stažení:";

$CONF_XTRA["texty"]["cs"]["setting-tip_nahrajte_fotografie"] = "Můžete nahrát až 4 fotografie, které se budou ve vaší záložce prolínat.";
$CONF_XTRA["texty"]["cs"]["setting-title_nahrajte_fotografie"] = "Nahrajte hlavní fotografii";
$CONF_XTRA["texty"]["cs"]["setting-title_video_zdroj"] = "Kde je video umístěno?";
$CONF_XTRA["texty"]["cs"]["setting-title_video_odkaz"] = "Vložte přímý odkaz na video";
$CONF_XTRA["texty"]["cs"]["setting-title_video_video_autoplay"] = "Přehrát okamžitě";
$CONF_XTRA["texty"]["cs"]["setting-title_video-label_video_hide_control"] = "Skrýt přehrávací lištu";

//$CONF_XTRA["class"]["setting-button_more-color_class"] = "color8";
$CONF_XTRA["texty"]["cs"]["setting-button_stahnout-ebook"] = "Stáhnout e-book";

//$CONF_XTRA["class"]["setting-button_more2-color_class"] = "color8";
$CONF_XTRA["texty"]["cs"]["setting-button_more2-text"] = "Stáhnout e-book nyní";

//$CONF_XTRA["class"]["setting-button_more4-color_class"] = "color2";
$CONF_XTRA["texty"]["cs"]["setting-button_more4-text"] = "Začít budovat databázi z Facebooku";

/* /TEXTY APLIKACE 6 BUDOVANI DATABAZE */

/* TEXTY APLIKACE 7 KOLO STESTI */
$CONF_XTRA["texty"]["cs"]["setting-default_text-nadpis_blahoprejeme"] = "BLAHOPŘEJEME VŠEM VÝHERCŮM";
$CONF_XTRA["texty"]["cs"]["setting-default_text-nadpis_jak_to_funguje"] = "JAK TO FUNGUJE?";
$CONF_XTRA["texty"]["cs"]["setting-default_text-text_jak_to_funguje"] = "Roztoč každý den kolo štěstí a vyčkej až se zastaví. Když budeš mít štěstí, odneseš si jednu ze skvělých výher.";
$CONF_XTRA["texty"]["cs"]["setting-default_text-nadpis_ziskejte"] = "ZÍSKEJTE DALŠÍ POKUSY";
$CONF_XTRA["texty"]["cs"]["setting-default_text-text_ziskejte"] = "Další pokusy můžeš získat splněním tajných úkolů, které se ti zobrazí poté, co se kolo zastaví.";

$CONF_XTRA["texty"]["cs"]["pop-default_text-nevyslo_to_text"] = "<p class='title'>Nevyšlo to...</p><p>Přijďte zítra nebo získejte nový pokus. Další pokus získáte potvrzením své kontaktní e-mailové adresy.</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-novy_pokus_na_ceste_text"] = "<p class='title'>Nový pokus je na cestě k vám!</p><p>Ve vaší emailové schránce nejdete zprávu odeslanou z e-mailu #email_user#. Řiďte se pokyny v tomto e-mailu.</p>"; // string #email_user# se nahradi defaultnim emailem
$CONF_XTRA["texty"]["cs"]["pop-default_text-novy_pokus_nepovedlo_se_vam_mail_vyhledat"] = "<p>Nepovedlo se vám zprávu dohledat?</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-novy_pokus_nepovedlo_se_vam_mail_vyhledat_help"] = "<p>E-mail by měl dorazit do 10 minut. Pokud se tak nestane, zkontrolujte složku &quot;Spam&quot;, či &quot;Hromadné&quot; ve vaší schánce.</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-stesti_v_lasce_text"] = "<p class='title'>Dnes máte prostě štěstí v lásce...</p><p>Přijďte si pro svou výhru zítra, určitě budete mít více štěstí.</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-roztoc_znovu_text"] = "<p class='title'>Roztočte to znovu</p><p>Nevyšlo to, ale získali jste nový pokus</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-sdilet_fb_text"] = "<p class='title'>Tak to bylo o chlup</p><p>Přijďte zítra nebo získejte další pokus. Další pokus získáte odesláním pozvánky do soutěže.</p>";
$CONF_XTRA["texty"]["cs"]["pop-default_text-gratulujeme_vyhravas"] = "<p class='title'>Gratulujeme! Vyhráváš!</p>";

$CONF_XTRA["texty"]["cs"]["setting-button_sdilet"] = "Sdílet";
$CONF_XTRA["texty"]["cs"]["setting-button_vyzvedni_vyhru"] = "Vyzvedni si výhru";
$CONF_XTRA["texty"]["cs"]["pop-default_text-podekovani_k_vyhre"] = "Děkujeme za účast v soutěži!";
$CONF_XTRA["texty"]["cs"]["pop-default_text-vsechny_ceny_jiz_byly_rozdany"] = "<p class='title'>Všechny ceny již byly rozdány!</p> <p>Sledujte naši stránku a už vám nic neuteče!</p>";


$CONF_XTRA["texty"]["cs"]["setting-email_potvrzeni-subject"] = "Potvrzení vaší e-mailové adresy ze soutěže Kola štestí";
$CONF_XTRA["texty"]["cs"]["setting-email_potvrzeni-body1"] = "<p>Potvrzení vaší e-mailové adresy ze soutěže Kola štestí provedete kliknutím na níže uvedenou adresu:</p>";
$CONF_XTRA["texty"]["cs"]["setting-email_potvrzeni-body2"] = "<p>Pokud jste dostali tento e-mail neoprávněně prosím ignorujte ho</p>";


$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FORMULAR_APP-7_text"] = "Jaká pole mají vyplňovat soutěžící v případě, že si vytočí výhru? Políčka ve formuláři můžete libovolně editovat kliknutím. Následné změny uložte a pokračujte na &quot;Další stranu&quot;.";

$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FB_OG_title"] = "Nastavte si Facebook sdílení";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FB_OG_text"] = "Klikněte na tlačítko Sdílet, přidejte obrázek a upravte texty";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-FB_OG_button"] = "Ok, jdu na to.";

/* / TEXTY APLIKACE 7 KOLO STESTI */


/* / TEXTY APLIKACE 8 INSTAGRAM */
$CONF_XTRA["texty"]["cs"]["setting-APP-8-default_text-title"] = "<p>Sleduj náš profil na Instagramu a budeš mít jako první přehled o všem, co pro vás připravujeme! Pravidelně přidáváme fotografie z tréninků, akcí a posiloven, takže se na našem profilu můžeš objevit i ty! :)</p>";
$CONF_XTRA["texty"]["cs"]["setting-APP-8-login_instagram_intro"] = "<h2>Připojte Instagram účet</h2><p>Nejprve s aplikací propojte Instagram, ze kterého chcete<br /> načíst vaše fotografie.</p>";
$CONF_XTRA["texty"]["cs"]["setting-APP-8-login_instagram"] = "Přihlásit se na Instagram";
$CONF_XTRA["texty"]["cs"]["setting-APP-8-logout_instagram"] = "odhlásit";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-INSTAGRAM-LOGIN_title"] = "Přihlášení do aplikace Instagram";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-INSTAGRAM-LOGIN_text"] = "K propojení aplikace s vaším Instagram účtem musíte být přihlášeni v prohlížeči na www.instagram.com. Pokud přihlášení nejste, Instagram vás k tomu vyzve ve vyskakovacím okně.";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-INSTAGRAM-chanell_set_title"] = "Nastavení zdroje pro fotky ve vaší aplikaci";
$CONF_XTRA["texty"]["cs"]["HELP-TEXT_SET-INSTAGRAM-chanell_set_text"] = "Můžete přepínat mezi dvěma zdroji pro fotografie ve vaší aplikaci. Váš profil na instagramu pod kterým jste přihlášeni v aplikaci, nebo hashtag, který vyplníte do druhé kolonky.";


/* picedit */
$CONF_XTRA["texty"]["cs"]["picedit-copy_paste"] = "nebo obrázek zkopírujte a vložte";
$CONF_XTRA["texty"]["cs"]["picedit-pen_tool"] = "Tužka";
$CONF_XTRA["texty"]["cs"]["picedit-crop_tool"] = "Oříznout";
$CONF_XTRA["texty"]["cs"]["picedit-rotate_tool"] = "Otočit";
$CONF_XTRA["texty"]["cs"]["picedit-resize_tool"] = "Změnit velikost";
$CONF_XTRA["texty"]["cs"]["picedit-message_vlozte-obrazek_nebo_fotak"] = "Vložte obrázek nebo připojte kameru a pořiďte fotografii";
$CONF_XTRA["texty"]["cs"]["picedit-message_working"] = "Pracuji ...";
$CONF_XTRA["texty"]["cs"]["picedit-message_sorry_no_webRTC"] = "Pardon, váš prohlížeč nepodporuje standard WebRTC!";
$CONF_XTRA["texty"]["cs"]["picedit-message_please_wait"] = "Prosím počkejte";
$CONF_XTRA["texty"]["cs"]["picedit-message_no_video_source_detected"] = "Nebyla detekováno žádné video! Prosím povolte kameře přístup";
$CONF_XTRA["texty"]["cs"]["picedit-message_FormData_API_is_not_supported"] = "Pracuji ...";
$CONF_XTRA["texty"]["cs"]["picedit-message_please_wait_uploading"] = "Prosím počkejte... Nahrávám... Nahráno";
$CONF_XTRA["texty"]["cs"]["picedit-message_please_wait_uploading_done"] = "nahráno";
$CONF_XTRA["texty"]["cs"]["picedit-message_data_submited"] = "Data byla v pořádku odeslána";
$CONF_XTRA["texty"]["cs"]["picedit-width"] = "Šířka";
$CONF_XTRA["texty"]["cs"]["picedit-height"] = "Výška";
$CONF_XTRA["texty"]["cs"]["picedit-color_black"] = "Černá";
$CONF_XTRA["texty"]["cs"]["picedit-color_white"] = "Bílá";
$CONF_XTRA["texty"]["cs"]["picedit-color_red"] = "Červená";
$CONF_XTRA["texty"]["cs"]["picedit-color_green"] = "Zelená";
$CONF_XTRA["texty"]["cs"]["picedit-color_orange"] = "Oranžová";
$CONF_XTRA["texty"]["cs"]["picedit-color_blue"] = "Modrá";
$CONF_XTRA["texty"]["cs"]["picedit-pen_large"] = "Velká";
$CONF_XTRA["texty"]["cs"]["picedit-pen_medium"] = "Střední";
$CONF_XTRA["texty"]["cs"]["picedit-pen_small"] = "Malá";
$CONF_XTRA["texty"]["cs"]["picedit-confirm_delete_from_disk"] = "Obrázek bude odstraněn i z vašeho náhledu aplikace. Opravdu smazat?";
$CONF_XTRA["texty"]["cs"]["picedit-tiptext_before"] = "<h2>Přidejte do grafiky vlastní prvky</h2>
<p>Nahrajte například logo vaší firmy, upravte ho a přidejte do grafiky vaší aplikace.<br /> Pro nejlepší výsledek doporučujeme nahrávat obrázky ve formátu *.png</p>";
$CONF_XTRA["texty"]["cs"]["picedit-tiptext_after"] = "<h2>Úprava vašeho obrázku</h2>
<p>Využijte nástroje v liště a až bude obrázek připravený, klikněte<br /> na zelené tlačítko <strong>Nahrát</strong></p>";

/* /picedit */

/* texty DUVOD ZRUSENI PREMIUM CLENSTVI */
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_1"] = 'Tuto službu nemám kde využít, není pro mě';
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_2"] = 'Momentálně nemám čas se službou zabývat';
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_3"] = 'Měsíční poplatek je vysoký';
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_4"] = 'Celkově mi služba nevyhovuje';
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_5"] = 'Ani jedna z dostupných aplikací mi nevyhovuje';
$CONF_XTRA["texty"]["cs"]["premium_cancel_reason_6"] = 'Efektivita aplikací nesplnila moje očekávání'; 
/* /DUVOD ZRUSENI PREMIUM CLENSTVI */


/* CENY APLIKACI */
// 1) aplikace_typ_id = 1, fotosoutez
$CONF_XTRA["price"][1]["STAV"] = "placena";
$CONF_XTRA["price"][1]["MONTH"] = 4500;
$CONF_XTRA["price"][1]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][1]["6M_DISCOUNT"] = "0.20"; // 20% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][1]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!

// 2) aplikace_typ_id = 2, trezor
$CONF_XTRA["price"][2]["STAV"] = "placena";
$CONF_XTRA["price"][2]["MONTH"] = 2900;
$CONF_XTRA["price"][2]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][2]["6M_DISCOUNT"] = "0.20"; // 20% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][2]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!
// $CONF_XTRA["price"][2]["YEAR"] = $CONF_XTRA["price"][2]["MONTH"] * 12 * (1-$CONF_XTRA["price"][2]["YEAR_DISCOUNT"]); // 10% sleva pri platbe na cely rok dopredu!

// 3) aplikace_typ_id = 3, kviz
$CONF_XTRA["price"][3]["STAV"] = "placena";
$CONF_XTRA["price"][3]["MONTH"] = 2300;
$CONF_XTRA["price"][3]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][3]["6M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][3]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!


// 4) aplikace_typ_id = 4, zalozka
$CONF_XTRA["price"][4]["STAV"] = "placena";
$CONF_XTRA["price"][4]["MONTH"] = 100;
if($_SERVER["REMOTE_ADDR"] == "84.42.152.67")
	$CONF_XTRA["price"][4]["MONTH"] = 1;
$CONF_XTRA["price"][4]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][4]["6M_DISCOUNT"] = "0.20"; // 20% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][4]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!

// 5) aplikace_typ_id = 5, sdileni videa
$CONF_XTRA["price"][5]["STAV"] = "placena";
$CONF_XTRA["price"][5]["MONTH"] = 100;
$CONF_XTRA["price"][5]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][5]["6M_DISCOUNT"] = "0.20"; // 20% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][5]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!

// 6) aplikace_typ_id = 6, ziskej zdarma
$CONF_XTRA["price"][6]["STAV"] = "placena";
$CONF_XTRA["price"][6]["MONTH"] = 990;
$CONF_XTRA["price"][6]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][6]["6M_DISCOUNT"] = "0.20"; // 20% sleva pri platbe na 6 mesicu dopredu!
$CONF_XTRA["price"][6]["12M_DISCOUNT"] = "0.30"; // 30% sleva pri platbe na 9 mesicu dopredu!

// 7) aplikace_typ_id = 7, kolo stesti
$CONF_XTRA["price"][7]["STAV"] = "placena";
$CONF_XTRA["price"][7]["MONTH"] = 2900;
$CONF_XTRA["price"][7]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][7]["6M_DISCOUNT"] = "0.20"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][7]["12M_DISCOUNT"] = "0.30"; // 20% sleva pri platbe na 9 mesicu dopredu!

// 8) aplikace_typ_id = 8, instagram
$CONF_XTRA["price"][8]["STAV"] = "placena";
$CONF_XTRA["price"][8]["MONTH"] = 150;
$CONF_XTRA["price"][8]["3M_DISCOUNT"] = "0.10"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][8]["6M_DISCOUNT"] = "0.20"; // 10% sleva pri platbe na 3 mesice dopredu!
$CONF_XTRA["price"][8]["12M_DISCOUNT"] = "0.30"; // 20% sleva pri platbe na 9 mesicu dopredu!


/* /CENY APLIKACI */

?>
