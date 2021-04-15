package uwb.licencjat.security;

import org.passay.*;
import org.springframework.stereotype.Service;

import java.util.Arrays;

@Service
public class PasswordGeneratorService {

    public String generatePassword(){
        PasswordGenerator gen = new PasswordGenerator();
        String password = gen.generatePassword(10, Arrays.asList(
                new CharacterRule(EnglishCharacterData.UpperCase, 2),
                new CharacterRule(EnglishCharacterData.LowerCase, 2),
                new CharacterRule(new CharacterData() {
                    @Override
                    public String getErrorCode() {
                        return "Błąd znaku specialnego";
                    }

                    @Override
                    public String getCharacters() {
                        return "!@#$%^&*()";
                    }
                }, 2),
                new CharacterRule(EnglishCharacterData.Digit, 2)
        ));
        return password;
    }
}
