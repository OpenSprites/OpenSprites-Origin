//Work in progress
//This javascript will work in the index as a tip giver that randomly gives different kinds of tips
//EdenStudio
   var tips = new Array ();
   tips[0] = "I give tips";
   tips[1] = "The easiest tips";
   tips[2] = "The coolest tips";
   tips[3] = "About time i give tips";
   tips[4] = "Tips pls";
   tips[5] = "Drinking Coke is not good for your health";
   tips[6] = "Tips are helpful in life - but some of them arent";
   tips[7] = "Scratch is the coolest place in the world to be, but please be nice to eachother";
   tips[8] = "Colour > Color";
   tips[9] = "Did you know Canada has polar bears that apologize to eachother profusely?";
   tips[10] = "Theres 7 billion people in the world";

   var tipCount = 0;

   for (i=0; i<tips.length; i++)
   {
      if (tips[i] == "")
         break;
      tipCount++;
   }

   var randomtips = document.getElementById('randomtips');
   randomtips.innerHTML = tips[Math.floor(tipCount * Math.random())];

