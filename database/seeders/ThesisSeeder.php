<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Thesis;
use App\Models\Author;
use App\Models\ThesisCopy;
use Carbon\Carbon;

class ThesisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thesesData = [
            [
                'title' => 'EXPERIENCES OF MOTHERS WHO SUFFERED MAJOR DEPRESSIVE DISORDER WITH PERIPARTUM ONSET',
                'abstract' => 'Women has a big role in the family not only as a wife but also as a mother and motherhood is not always delightful as it seems to be. The purpose of this qualitative study is to explore more detailed experiences of mothers who suffered Major Depressive Disorder with peripartum onset. These includes their struggles during and after giving birth, their own ways of recovery, lessons they have learned throughout their experience and on how did MDD influenced their relationship with others. This consist of three mothers who were clinically diagnosed with Major Depressive Disorder with peripartum onset. Thematic analysis was used to analyze and from themes fits for their life circumstances. Research findings show that relationship with other people can really influence an individual\'s life. Having unstable moods and emotions and suicidal thoughts due to their difficulty of handling current life situation are some struggles of mothers with MDD. Mothers with MDD basic ways of recovery was therapy, by taking medications and by the help of their support group. Lastly, life lessons they learned throughout the entire time of depression was having a support system that will give them sense of belongingness and that are willing to help them, praying to God, self-love, determination to recover, and being educated about the said condition will always be a big help.',
                'year_published' => 2019,
                'department' => 'AB Psychology',
                'authors' => ['Alyssa Mae Sadural', 'Crisanta Nicole Mamansag', 'Mary Joy Viduya'],
                'created_at' => '2025-10-28 08:11:55'
            ],
            [
                'title' => 'INTOLERANCE OF UNCERTAINTY AND PSYCHOLOGICAL WELL-BEING OF OLD AGE: THE MODERATED EFFECT OF COGNITIVE EMOTION REGULATION',
                'abstract' => 'As old age experiences intolerance of uncertainty, psychological well-being decreases. However, with the help of cognitive emotion regulation, the negative association between the two variables will be moderated. The 12-item Intolerance of Uncertainty Scale short version (Carleton, 2007), 18-item scale of Psychological Well-being (Ryff, 1989), and 36-item adaptation Cognitive Emotion Regulation Scale (Garnefski et al., 1999) were utilized for 344 respondents.',
                'year_published' => 2023,
                'department' => 'BS Psychology',
                'authors' => ['Jubilee Hidalgo', 'Felix Iglesia', 'Cristella Marie Patricio', 'Dianne Elaine Sakay'],
                'created_at' => '2025-10-28 08:15:22'
            ],
            [
                'title' => 'THE MEDIATING ROLE OF NATURE CONNECTEDNESS IN THE RELATIONSHIP BETWEEN SPIRITUALITY AND PRO-ENVIRONMENTAL BEHAVIOR AMONG UNIVERSITY STUDENTS',
                'abstract' => 'As global environmental issues increase, understanding interal motivators of pro-environmental behavior (PEB) becomes important. This study investigated the mediating role of nature connectedness (NC) in the relationship between multiple dimensions of spirituality and pro-environmental behavior (PEB) among university students. Grounded in Ecological Self Theory, the research posits that spirituality— defined as a personal search for meaning connected to a higher power or purpose can foster a stronger emotional bond with nature, thereby enhancing sustainable behaviors. A quantitative correlational design was employed, using validated scales for spirituality, NC, and PEB among 375 students at Central Luzon State University. Pearson correlation and mediation analysis via Hayes\' PROCESS Macro revealed significant positive relationships among all three variables. Spirituality was moderately associated with both NC and PEB, while NC also significantly predicted PEB. Mediation analysis confirmed that NC partially mediated the relationship between spirituality and PEB, accounting for 25.9% of the total effect. These findings support the hypothesis that spirituality enhances environmental behavior directly and indirectly by strengthening one\'s connection to nature. The study contributes to the understanding of psychological drivers of sustainability and highlights the importance of integrating spiritual and nature-based frameworks into environmental education. Recommendations include expanding the sample to multiple institutions and incorporating qualitative methods for deeper insight. This research underscores the potential of spiritual engagement and nature connectedness as transformative pathways to foster lasting environmental responsibility in young adults.',
                'year_published' => 2025,
                'department' => 'BS Psychology',
                'authors' => ['Daniella Noel Briones', 'Rizza Mae Del Rosario', 'Mia Paderes'],
                'created_at' => '2025-10-28 08:18:46'
            ],
            [
                'title' => 'CHALLENGES AND COPING MECHANISMS OF FRONTLINERS\' SPOUSES DURING THE COVID-19 PANDEMIC',
                'abstract' => 'Frontliners\' spouses are responsible for many tasks (Dekel et al., 2016). They struggle because of their responsibilities (Wilson & Murray, 2016). According to Du et al., 2020; Elbay et al., 2020, studies should also focus on how partners dealt with and managed the pandemic outbreak. Several studies have to olfer an expanded view that addresses the experiences of front liners spouses in different aspects of their challenges, such as marital, parenthood, and spiritual challenges. An investigation of more challenges and their influence creates significant patterns for future challenges, such as how they deal with their lives with their fear of their spouses\' safety at work. It may allow for a better understanding of coping mechanisms that may support them for better mental health. Future researchers can further investigate the support of spouses for one another through difficult times.',
                'year_published' => 2023,
                'department' => 'BS Psychology',
                'authors' => ['Vanessa Alegria', 'Apple Grace Gajes', 'Maricel Ignacio'],
                'created_at' => '2025-10-28 08:21:55'
            ],
            [
                'title' => 'THE MODERATING ROLE OF NEGATIVE AFFECT IN THE RELATIONSHIP BETWEEN GROWTH MINDSET AND RESILIENCE AMONG EMERGING ADULTS',
                'abstract' => 'The present study mainly explored the moderating effect of negative affect in the relationship between growth mindset and resilience among emerging adults. Emerging adult respondents (N = 376) were asked to answer questionnaires assessing the levels of their growth mindset, resilience, and negative affect. In line with this, a significant moderate correlation was found between growth mindset and resilience (r = .528; p < 001). Most importantly, the study revealed that negative affect significantly moderated the relationship between growth mindset and resilience among emerging adults (F (3, 372) = 63.35, p < .001, R2 = 3381). This means that even individuals with a strong growth mindset may experience diminished resilience when experiencing high levels of negative affect. Meanwhile, a strengthened relationship between growth mindset and resilience could be observed with the presence of low levels of negative affect. Thus, this study underscores the importance of investigating negative constructs as potential moderators that may strengthen or weaken positive relationships. These insights amplify the importance of exploring complex interplays between positive and negative factors. Finally, to further contextualize and validate these findings, additional research is recommended. Future studies could examine other contributing or interacting variables to deepen our understanding of the dynamics between negative affect, growth mindset, and resilience in emerging adults.',
                'year_published' => 2025,
                'department' => 'BS Psychology',
                'authors' => ['Jamila Abobo', 'Jalen Louise Galang', 'Mariecon Morales'],
                'created_at' => '2025-10-28 08:25:19'
            ],
            [
                'title' => 'BLUE ENCOUNTERS: EXPLORING THE LIVED EXPERIENCES OF ADOLESCENTS IN CONQUERING GRIEF',
                'abstract' => 'This qualitative research Investigates how bereaved Filipino adolescents experience and make sense of life after the loss of a significant loved one. Guided by the Continuing Bonds Theory (Klass, Silverman, & Nickman, 1996), the study examines how these adolescents sustain ongoing emotional and psychological connections with the deceased, even as they undergo personal growth, identity development, and cultural shifts. Through purposive sampling, six participants aged 10 to 18 each having experienced a loss within the past one to five years-were selected. Data collection involved semi-structured, in-depth interviews, with thematic analysis conducted following Braun and Clarke\'s (2006) approach. The analysis revealed three central themes: (1) Experiences after the loss, (2) What support the grieving journey, and (3) Individual\'s outlook in life, values, and future perspectives. In particular, the findings highlight the influence of Filipino cultural practices such as pakikiramay (sympathy, pananampalataya (faith), and strong family ties-in shaping how adolescents cope with bereavement. This study enriches current understandings of adolescent grief by emphasizing its relational and culturally embedded dimensions among Filipino youth. It calls for psychosocial support systems that are sensitive to both developmental stages and cultural contexts, acknowledging the importance of continuing bonds, emotional strength, and evolving identity. Overall, the research offers a more complex and compassionate view of adolescent bereavement within collectivist societies.',
                'year_published' => 2025,
                'department' => 'BS Psychology',
                'authors' => ['Jhanyn Velasco', 'Hermie Delarita'],
                'created_at' => '2025-10-28 08:27:46'
            ],
            [
                'title' => 'KARANASAN NG MGA LALAKING NAKAPAG-ASAWA NG BABAENG MAY ANAK',
                'abstract' => 'Isang kwalitatibo ang pag-aaral na ito na naglalayong pag-aralan ang karanasan ng mga lalaking nakapag-asawa ng babaeng may anak. Binubuo ng apat (4) na lalaki na may edad na animnaput-anim (26) hanggang apatnapung (40) taong gulang ang nagsilbing mga kalahok sa pag-aaral na ito. Ang bawat isa ay higit sa tatlong (3) taon ng kasal sa babaeng mayroong anak mula sa nakaraang karelasyon. Sa pamamagitan ng Modelo ng Pagdadala ni Decenteceo at Tematikong Pag-aanalisa nina Braun at Clarke (2013) ay naanalisa ang mga datos na nakalap mula sa mga kalahok. Lumalabas sa pag-aaral na ito na pangkaraniwan ang pinagdaanan ng mga lalaking nakapag-asawa ng babaeng may anak. Lumabas din na kadalasang dahilan ng pagkagusto ng lalaki sa babaeng may anak ay ang pagiging masipag, pagkakaroon ng malawak na pag-isip, maalalahanin, matiisin at ang pagiging mapagmahal. Napag-alaman sa pag-aaral na ito na ang karaniwang suliranin na pinagdaanan ay patungkol sa pinansiyal, bisyo, anak ng babae, tsismis, pag-aaway, pagbibigay sa anak at hindi pagkakasundo ng nanay at asawa. lan sa mga paraan ng mga lalaki sa pagharap sa suliranin ay ang pagsasawalang-bahala sa narinig, pagdiskarte sa trabaho at ang pantay na pagbibigay ng materyal na bagay sa mga anak. Matapos na makapag-asawa ng babaeng may anak ay lumabas na layunin ng mga lalaki na makapagtapos ng pag-aaral ang mga anak at magkaroon ng sariling pundar.',
                'year_published' => 2019,
                'department' => 'BS Psychology',
                'authors' => ['Angelica Calalang', 'Nenita Opiana', 'Patricia Mae Paulino'],
                'created_at' => '2025-10-28 08:31:12'
            ],
            [
                'title' => 'PSYCHOLOGICAL WELL-BEING AND SUBJECTIVE WELL-BEING OF CELL GROUP MEMBERS IN CENTRAL LUZON STATE UNIVERSITY',
                'abstract' => 'The purpose of this research is a.) to know what the socio-demographic characteristics of the cell group members (sex, age, and length of involvement), b.) to know the relationship between the socio-demographic characteristics of cell group members and psychological well-being. c.) to know the relationship between the socio-demographic characteristics of cell group members and subjective well-being, d.) to know if there are differences between sexes as well as their length of involvement with respect to their psychological well-being and subjective well-being, e.) to know the relationship of psychological well-being and subjective well-being, and f.) to know if being involved in cell have any influence on a persons\' psychological and subjective well-being. In the process of selection, the researchers included 50 females and 50 males to meet a quota of 100 respondents. The SPSS results showed there are no significant relationship between psychological and subjective well-being as well as their domains, except for the Positive Affect domain of subjective well-being with the Environmental Mastery, Positive Relationship with Others, and Purpose in life domains of psychological well-being.',
                'year_published' => 2019,
                'department' => 'BS Psychology',
                'authors' => ['Dianne Joy Castelo', 'Coline Dela Cruz'],
                'created_at' => '2025-10-28 08:33:41'
            ],
            [
                'title' => 'THE RELATIONSHIP OF SEX, SOCIO ECONOMIC STATUS, PARENTING STYLES, AND SCHOOL CLIMATE ON ACADEMIC PERFORMANCE OF COLLEGE STUDENTS ON SELECTED UNIVERSITIES IN NUEVA ECIJA',
                'abstract' => 'The present study aims to investigate the influence of Parental Socio economic status (parent\'s educational level, parent\'s occupational level and parent\'s income level) on student\'s academic performance. Also, the present study examines if parenting styles, sex, and school climate have significant influence on student\'s academic performance. Using descriptive correlational design, data was analyzed. It was found that socio economic status of both parents which includes their monthly income and occupational level have no significant influence on the academic performance of a certain student. While there is a very weak significant relationship between the educational attainment of the mother and the academic performance of the students who partaken in this study while the educational attainment of the father has no significant influence on the academic performance of the students. It was also found that there is a difference on how the males and females perform in school academically. Female students tend to perform better in school rather than male students. The current study also concluded that the parenting style of the parents was not a predictor of the academic performance of the students. In addition, school climate has an influence on the academic performance of students and this study shows that among the three dimensions of school climate, school engagement and school safety are the only dimensions that have a significant influence on the academic performance of a certain student.',
                'year_published' => 2019,
                'department' => 'AB Psychology',
                'authors' => ['Alessandro Austria', 'Nerissa Gamboa', 'Benn Joseph Saturno'],
                'created_at' => '2025-10-28 08:36:02'
            ],
            [
                'title' => 'KARANASAN SA PAGBANGON NG MGA BABAENG BIKTIMA NG PANGGAGAHASA.',
                'abstract' => 'Isang kwalitatibo ang pag-aaral na ito na may layuning alamin ang karanasan sa pagbangon ng mga babaeng biktima ng panggagahasa, partikular na ang mga bumubuo sa proseso ng kanilang pagbangon mula sa nangyaring panggagahasa. Ang mga kalahok sa pag-aaral na ito ay binubuo ng apat na babaeng biktima ng stranger rape. Dalawang taon pataas na ang nakalipas simula ng mangyari ang panggagahasa. Mayroong edad na dalawampu hanggang dalawampu\'t lima at kasalukuyang naninirahan sa Nueva Ecija. Nakipagkwentuhan at nakipanayam ang mga mananaliksik sa mga kalahok upang makakalap ng malalim at makabuluhang impormasyon. Sa pag-aanalisa naman ng datos ay Thematic Analysis ang ginamit ng mga mananaliksik upang maipaliwanag sa makabuluhang pagpapahayag ang mga nakalap na datos. Lumalabas sa pag-aaral na ito na ang proseso ng pagbangon ng mga biktima ng panggagahasa ay nagsisimula sa paghihirap dahil sa mga bagay na nakakasagabal at nagpapahirap sa kanilang pagbangon. Ang pagkakaroon ng negatibong pag-iisip, hindi nakamit na hustisya sa nangyaring panggagahasa at pagkaalala sa panggagahasa ay ang mga pangunahing nagpahirap sa muling pagbangon ng mga kalahok. Gayunpaman, may mga bagay rin na lubos na nakatulong sa mga kalahok upang sila ay makabangon, ang pagkakaroon ng social support mula sa mga taong malapit sa kanila, pagtuon ng kanilang atensyon sa mga positibong bagay, pananampalataya sa Diyos at pagpapatawad sa taong umabuso sa kanila. Lumalabas rin sa pag-aaral na sa proseso ng pagbangon ng mga kalahok ay mayroon silang mga positibong bagay na natutunan, ang magpatawad at lumapit sa Diyos upang humingi ng tulong.',
                'year_published' => 2019,
                'department' => 'AB Psychology',
                'authors' => ['Eunice Calibara', 'Selwyn Tungpalan', 'Claudine Vergara'],
                'created_at' => '2025-10-28 08:37:41'
            ]
        ];

        foreach ($thesesData as $thesisData) {
            // Create thesis
            $thesis = Thesis::create([
                'title' => $thesisData['title'],
                'abstract' => $thesisData['abstract'],
                'year_published' => $thesisData['year_published'],
                'department' => $thesisData['department'],
                'created_at' => Carbon::parse($thesisData['created_at']),
                'updated_at' => Carbon::parse($thesisData['created_at']),
            ]);

            // Create authors and attach to thesis
            foreach ($thesisData['authors'] as $authorName) {
                $nameParts = explode(' ', $authorName);
                $lastName = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);

                $author = Author::firstOrCreate(
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $thesis->authors()->attach($author->id);
            }

            // Create 2 copies for each thesis
            for ($i = 0; $i < 2; $i++) {
                ThesisCopy::create([
                    'thesis_id' => $thesis->id,
                    'is_available' => true,
                    'created_at' => Carbon::parse($thesisData['created_at']),
                    'updated_at' => Carbon::parse($thesisData['created_at']),
                ]);
            }
        }
    }
}